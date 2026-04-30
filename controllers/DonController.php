<?php
/**
 * Contrôleur Don
 * Gère le formulaire de don, l'appel API et la page de confirmation
 */

require_once __DIR__ . '/../models/Don.php';
require_once __DIR__ . '/../models/Verset.php';
require_once __DIR__ . '/../models/Parametre.php';

class DonController {

    private Don $donModel;
    private Verset $versetModel;
    private Parametre $parametreModel;

    public function __construct() {
        $this->donModel      = new Don();
        $this->versetModel   = new Verset();
        $this->parametreModel = new Parametre();
    }

    /**
     * Affiche le formulaire de don
     */
    public function formulaire(): void {
        // Récupère la liste des pays et opérateurs depuis l'API Ashtech Pay
        $paysOperateurs = $this->getPaysOperateurs();
        $params = $this->parametreModel->tous();
        require __DIR__ . '/../views/don.php';
    }

    /**
     * Traite la soumission du formulaire de don
     */
    public function traiter(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php');
            exit;
        }

        // Validation et nettoyage des entrées
        $type             = in_array($_POST['type'] ?? '', ['anonyme', 'identifie']) ? $_POST['type'] : 'anonyme';
        $montant          = (float)filter_var($_POST['montant'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $methode          = in_array($_POST['methode'] ?? '', ['api_mobile_money', 'virement_bancaire']) ? $_POST['methode'] : 'api_mobile_money';
        $nom              = htmlspecialchars(trim($_POST['nom'] ?? ''));
        $prenom           = htmlspecialchars(trim($_POST['prenom'] ?? ''));
        $pays             = htmlspecialchars(trim($_POST['pays'] ?? ''));
        $ville            = htmlspecialchars(trim($_POST['ville'] ?? ''));
        $telephone        = htmlspecialchars(trim($_POST['telephone'] ?? ''));
        $email            = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL) ?: null;
        $operateur        = htmlspecialchars(trim($_POST['operateur'] ?? ''));
        $code_pays_tel    = htmlspecialchars(trim($_POST['code_pays_tel'] ?? ''));
        $otp              = htmlspecialchars(trim($_POST['otp'] ?? ''));
        $notify_url       = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/index.php?page=webhook';

        if ($montant <= 0) {
            $_SESSION['erreur'] = 'Le montant doit être supérieur à 0.';
            header('Location: index.php?page=don');
            exit;
        }

        // Créer le don en BDD avec statut "en_attente"
        $donId = $this->donModel->creer([
            'type'             => $type,
            'nom'              => $type === 'identifie' ? $nom : null,
            'prenom'           => $type === 'identifie' ? $prenom : null,
            'pays'             => $type === 'identifie' ? $pays : null,
            'ville'            => $type === 'identifie' ? $ville : null,
            'telephone'        => $telephone,
            'email'            => $email,
            'montant'          => $montant,
            'methode_paiement' => $methode,
            'operateur'        => $operateur,
            'code_pays_tel'    => $code_pays_tel,
            'statut'           => 'en_attente',
        ]);

        // ============================================================
        // PAIEMENT VIA API ASHTECH PAY (Mobile Money)
        // ============================================================
        if ($methode === 'api_mobile_money') {
            $resultat = $this->appelApiPaiement([
                'amount'       => $montant,
                'currency'     => $this->parametreModel->get('devise', 'XAF'),
                'phone'        => $telephone,
                'operator'     => $operateur,
                'country_code' => $code_pays_tel,
                'reference'    => 'DON-' . $donId,
                'notify_url'   => $notify_url,
                'otp'          => $otp ?: null,
            ]);

            // Gestion des différents flux de réponse
            if ($resultat['http_code'] === 202) {
                $body = $resultat['body'];
                $reference = $body['transaction_id'] ?? null;
                $this->donModel->mettreAJourStatut($donId, 'en_attente', $reference);

                // Flux Wave : rediriger vers le lien Wave
                if (isset($body['flow']) && $body['flow'] === 'wave' && isset($body['wave_url'])) {
                    $_SESSION['don_id']    = $donId;
                    $_SESSION['wave_url']  = $body['wave_url'];
                    $_SESSION['montant']   = $montant;
                    header('Location: index.php?page=wave');
                    exit;
                }

                // Flux USSD Push : attendre webhook
                $_SESSION['don_id']  = $donId;
                $_SESSION['montant'] = $montant;
                $_SESSION['ref']     = $reference;
                header('Location: index.php?page=merci&don_id=' . $donId);
                exit;

            } elseif ($resultat['http_code'] === 400 && ($resultat['body']['error'] ?? '') === 'otp_required') {
                // OTP requis : afficher le formulaire OTP
                $_SESSION['otp_required']  = true;
                $_SESSION['ussd_code']     = $resultat['body']['ussd_code'] ?? null;
                $_SESSION['don_id_pending']= $donId;
                $_SESSION['form_data']     = $_POST; // Pour repopuler le formulaire
                header('Location: index.php?page=don&etape=otp&don_id=' . $donId);
                exit;

            } else {
                // Erreur API
                $this->donModel->mettreAJourStatut($donId, 'echec');
                $_SESSION['erreur'] = $resultat['body']['message'] ?? 'Erreur lors du paiement. Veuillez réessayer.';
                header('Location: index.php?page=don');
                exit;
            }
        }

        // ============================================================
        // VIREMENT BANCAIRE : confirmation directe
        // ============================================================
        $this->donModel->mettreAJourStatut($donId, 'en_attente', 'VIREMENT-' . date('YmdHis'));
        $_SESSION['don_id']  = $donId;
        $_SESSION['montant'] = $montant;
        header('Location: index.php?page=merci&don_id=' . $donId);
        exit;
    }

    /**
     * Page de remerciement
     */
    public function merci(): void {
        $donId = (int)($_GET['don_id'] ?? $_SESSION['don_id'] ?? 0);
        $don   = $donId ? $this->donModel->trouverParId($donId) : null;
        $verset = $this->versetModel->aleatoire();
        require __DIR__ . '/../views/merci.php';
    }

    /**
     * Endpoint AJAX : retourne le statut actuel d'un don (JSON)
     * Si le don est encore "en_attente", on interroge directement l'API Ashtech Pay
     * pour récupérer le statut réel et mettre à jour la BDD.
     */
    public function checkStatut(): void {
        header('Content-Type: application/json');
        $donId = (int)($_GET['don_id'] ?? 0);
        if ($donId <= 0) {
            echo json_encode(['statut' => 'inconnu']);
            exit;
        }
        $don = $this->donModel->trouverParId($donId);
        if (!$don) {
            echo json_encode(['statut' => 'inconnu']);
            exit;
        }

        // Si déjà finalisé, on retourne directement le statut local
        if (in_array($don['statut'], ['succes', 'echec'], true)) {
            echo json_encode([
                'statut'    => $don['statut'],
                'reference' => $don['reference_transaction'] ?? '',
            ]);
            exit;
        }

        // En attente : on interroge l'API Ashtech Pay pour avoir le vrai statut
        if ($don['methode_paiement'] === 'api_mobile_money' && !empty($don['reference_transaction'])) {
            $statutApi = $this->verifierStatutApi(
                $don['reference_transaction'],
                'DON-' . $donId
            );
            if ($statutApi === 'succes' || $statutApi === 'echec') {
                $this->donModel->mettreAJourStatut($donId, $statutApi, $don['reference_transaction']);
                echo json_encode([
                    'statut'    => $statutApi,
                    'reference' => $don['reference_transaction'],
                ]);
                exit;
            }
        }

        echo json_encode([
            'statut'    => $don['statut'],
            'reference' => $don['reference_transaction'] ?? '',
        ]);
        exit;
    }

    /**
     * Endpoint appelé par le JS quand le compteur arrive à 0.
     * Marque le don en échec si toujours en attente.
     */
    public function marquerEchec(): void {
        header('Content-Type: application/json');
        $donId = (int)($_GET['don_id'] ?? 0);
        if ($donId > 0) {
            $don = $this->donModel->trouverParId($donId);
            if ($don && $don['statut'] === 'en_attente') {
                // Avant de marquer échec, on essaie une dernière vérification API
                $statutFinal = 'echec';
                if ($don['methode_paiement'] === 'api_mobile_money' && !empty($don['reference_transaction'])) {
                    $statutApi = $this->verifierStatutApi(
                        $don['reference_transaction'],
                        'DON-' . $donId
                    );
                    if ($statutApi === 'succes') {
                        $statutFinal = 'succes';
                    }
                }
                $this->donModel->mettreAJourStatut($donId, $statutFinal, $don['reference_transaction']);
                echo json_encode(['ok' => true, 'statut' => $statutFinal]);
                exit;
            }
        }
        echo json_encode(['ok' => true]);
        exit;
    }

    /**
     * Page d'attente Wave
     */
    public function wave(): void {
        $waveUrl = $_SESSION['wave_url'] ?? '#';
        $montant = $_SESSION['montant'] ?? 0;
        $donId   = $_SESSION['don_id'] ?? 0;
        require __DIR__ . '/../views/wave.php';
    }

    /**
     * Webhook Ashtech Pay (POST)
     * Reçoit les notifications de paiement
     * Supporte plusieurs formats possibles de payload
     */
    public function webhook(): void {
        // Toujours répondre 200 immédiatement
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['received' => true]);

        $payload = file_get_contents('php://input');
        $data    = json_decode($payload, true);

        // Log du webhook pour debug
        @file_put_contents(
            __DIR__ . '/../webhook.log',
            '[' . date('Y-m-d H:i:s') . '] ' . $payload . PHP_EOL,
            FILE_APPEND
        );

        if (!$data) return;

        // Référence : peut être à plusieurs endroits selon le format
        $reference = $data['reference']
            ?? $data['merchant_reference']
            ?? $data['data']['reference']
            ?? $data['transaction']['reference']
            ?? '';

        // Transaction ID : idem
        $transactionId = $data['transaction_id']
            ?? $data['id']
            ?? $data['data']['transaction_id']
            ?? $data['transaction']['id']
            ?? null;

        // Statut/event : peut être 'event', 'status', 'state'…
        $statutRaw = strtolower(
            $data['event']
            ?? $data['status']
            ?? $data['state']
            ?? $data['data']['status']
            ?? $data['transaction']['status']
            ?? ''
        );

        // Extraire l'ID du don depuis la référence "DON-123"
        $donId = (int)str_replace('DON-', '', $reference);
        if ($donId <= 0) return;

        $statutsSucces = [
            'payment.completed', 'payment.success', 'payment.successful',
            'transaction.success', 'transaction.completed',
            'success', 'successful', 'completed', 'paid', 'reussi', 'réussi'
        ];
        $statutsEchec = [
            'payment.failed', 'payment.cancelled', 'payment.canceled',
            'transaction.failed', 'transaction.cancelled',
            'failed', 'failure', 'cancelled', 'canceled', 'rejected', 'echec', 'échec'
        ];

        if (in_array($statutRaw, $statutsSucces, true)) {
            $this->donModel->mettreAJourStatut($donId, 'succes', $transactionId);
        } elseif (in_array($statutRaw, $statutsEchec, true)) {
            $this->donModel->mettreAJourStatut($donId, 'echec', $transactionId);
        }
    }

    /**
     * Interroge l'API Ashtech Pay pour récupérer le statut d'une transaction.
     * Retourne 'succes', 'echec' ou 'en_attente'.
     */
    private function verifierStatutApi(string $transactionId, string $reference): string {
        $apiKey  = 'ak_83adbb920ef3efd424561f70d6b76e7bf0ed91cce302973a';
        $baseUrl = 'https://ashtechpay.top';

        // Plusieurs endpoints possibles pour la vérification du statut
        $endpoints = [
            $baseUrl . '/v1/transactions/' . urlencode($transactionId),
            $baseUrl . '/v1/collect/' . urlencode($transactionId),
            $baseUrl . '/v1/transaction?reference=' . urlencode($reference),
            $baseUrl . '/v1/payments/' . urlencode($transactionId),
        ];

        foreach ($endpoints as $url) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER     => [
                    'Authorization: Bearer ' . $apiKey,
                    'Accept: application/json',
                ],
                CURLOPT_TIMEOUT        => 8,
                CURLOPT_SSL_VERIFYPEER => true,
            ]);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200 && $response) {
                $data = json_decode($response, true);
                if (!is_array($data)) continue;

                $status = strtolower(
                    $data['status']
                    ?? $data['state']
                    ?? $data['payment_status']
                    ?? $data['data']['status']
                    ?? $data['transaction']['status']
                    ?? ''
                );

                if (in_array($status, [
                    'success', 'successful', 'completed', 'paid', 'reussi', 'réussi'
                ], true)) {
                    return 'succes';
                }
                if (in_array($status, [
                    'failed', 'failure', 'cancelled', 'canceled', 'rejected', 'echec', 'échec', 'expired'
                ], true)) {
                    return 'echec';
                }
                // statut connu mais pas final → on est en attente
                return 'en_attente';
            }
        }

        return 'en_attente';
    }

    // ============================================================
    // METHODES PRIVEES
    // ============================================================

    /**
     * Récupère les pays et opérateurs depuis l'API Ashtech Pay
     */
    private function getPaysOperateurs(): array {
        // =========================================================
        // INSERER ICI LES PARAMETRES DE MON API DE PAIEMENT
        // Clé API Ashtech Pay
        // =========================================================
        $apiKey  = 'ak_83adbb920ef3efd424561f70d6b76e7bf0ed91cce302973a';
        $baseUrl = 'https://ashtechpay.top';

        $ch = curl_init($baseUrl . '/v1/countries');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $apiKey],
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && $response) {
            return json_decode($response, true) ?: $this->paysFallback();
        }
        return $this->paysFallback();
    }

    /**
     * Données de secours si l'API est inaccessible
     */
    private function paysFallback(): array {
        return [
            ['code' => 'CM', 'name' => 'Cameroun',     'currency' => 'XAF', 'operators' => ['MTN Mobile Money', 'Orange Money']],
            ['code' => 'SN', 'name' => 'Sénégal',      'currency' => 'XOF', 'operators' => ['Free Money', 'Orange Money', 'Wave']],
            ['code' => 'CI', 'name' => "Côte d'Ivoire",'currency' => 'XOF', 'operators' => ['Moov Money', 'MTN Mobile Money', 'Orange Money', 'Wave']],
            ['code' => 'BJ', 'name' => 'Bénin',        'currency' => 'XOF', 'operators' => ['Moov Money', 'MTN Mobile Money']],
            ['code' => 'BF', 'name' => 'Burkina Faso', 'currency' => 'XOF', 'operators' => ['Moov Money', 'Orange Money']],
            ['code' => 'ML', 'name' => 'Mali',         'currency' => 'XOF', 'operators' => ['Moov Money', 'Orange Money']],
            ['code' => 'GN', 'name' => 'Guinée',       'currency' => 'GNF', 'operators' => ['MTN Mobile Money', 'Orange Money']],
            ['code' => 'CD', 'name' => 'RD Congo',     'currency' => 'CDF', 'operators' => ['Airtel Money', 'Orange Money', 'Vodacom M-Pesa']],
            ['code' => 'CG', 'name' => 'Congo',        'currency' => 'XAF', 'operators' => ['Airtel Money', 'MTN Mobile Money']],
            ['code' => 'TG', 'name' => 'Togo',         'currency' => 'XOF', 'operators' => ['Flooz (Moov)', 'T-Money']],
            ['code' => 'NE', 'name' => 'Niger',        'currency' => 'XOF', 'operators' => ['Airtel Money']],
            ['code' => 'TD', 'name' => 'Tchad',        'currency' => 'XAF', 'operators' => ['Airtel Money', 'Moov Money']],
            ['code' => 'GA', 'name' => 'Gabon',        'currency' => 'XAF', 'operators' => ['Airtel Money', 'Moov Money']],
        ];
    }

    /**
     * Appel à l'API Ashtech Pay pour initier un paiement
     * =========================================================
     * INSERER ICI LES PARAMETRES DE MON API DE PAIEMENT
     * Endpoint : https://ashtechpay.top/v1/collect
     * Méthode  : POST
     * Auth     : Bearer token
     * =========================================================
     */
    private function appelApiPaiement(array $params): array {
        // === CONFIGURATION API ===
        $apiKey   = 'ak_83adbb920ef3efd424561f70d6b76e7bf0ed91cce302973a'; // Votre clé API
        $endpoint = 'https://ashtechpay.top/v1/collect';                    // Endpoint de collecte

        // Construire le corps de la requête
        $body = [
            'amount'       => $params['amount'],
            'currency'     => $params['currency'],
            'phone'        => $params['phone'],
            'operator'     => $params['operator'],
            'country_code' => $params['country_code'],
            'reference'    => $params['reference'],
            'notify_url'   => $params['notify_url'],
        ];
        // Ajouter l'OTP si fourni
        if (!empty($params['otp'])) {
            $body['otp'] = $params['otp'];
        }

        $ch = curl_init($endpoint);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($body),
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json',
            ],
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error    = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return ['http_code' => 500, 'body' => ['message' => 'Erreur réseau: ' . $error]];
        }

        return [
            'http_code' => $httpCode,
            'body'      => json_decode($response, true) ?? [],
        ];
    }
}
