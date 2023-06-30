<?php
    // Генерация случайного пароля
    function generateRandomPassword($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $index = mt_rand(0, strlen($characters) - 1);
            $password .= $characters[$index];
        }
        return $password;
    }

    // Генерация кода для подтверждения регистрации
    function generateConfirmationCode() {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $codeLength = 10;
        $code = '';
    
        for ($i = 0; $i < $codeLength; $i++) {
            $randomIndex = rand(0, strlen($characters) - 1);
            $code .= $characters[$randomIndex];
        }
    
        return $code;
    }

    // Функция валидации электронной почты
    function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    // Функция экранирования строки
    function escape($value)
    {
        global $mysqli;
        
        $escapedValue = mysqli_real_escape_string($mysqli, $value);
        return $escapedValue;
    }

    // Функция ограничения числа попыток ввода пароля
    function limitLoginAttempts($username, $maxAttempts = 5, $lockoutDuration = 1800)
    {
        $attemptsKey = 'login_attempts_' . $username;
        $attempts = (int) $_SESSION[$attemptsKey] ?? 0;

        if ($attempts >= $maxAttempts) {
            $_SESSION['login_lockout'] = time() + $lockoutDuration;
            return false;
        }

        return true;
    }

    // Функция генерации токена для защиты от CSRF атак
    function generateCSRFToken()
    {
        $token = generateRandomString();
        $_SESSION['csrf_token'] = $token;
        return $token;
    }

    // Функция проверки валидности токена CSRF
    function verifyCSRFToken($token)
    {
        if (isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $token) {
            unset($_SESSION['csrf_token']);
            return true;
        }
        return false;
    }

    // Функция проверки активной сессии
    function verifySession()
    {
        if (isset($_SESSION['user_id']) && isset($_SESSION['last_activity'])) {
            $lastActivity = $_SESSION['last_activity'];
            $sessionExpiration = 60 * 30; // Время истечения сессии (30 минут)
            if (time() - $lastActivity <= $sessionExpiration) {
                // Обновление времени последней активности
                $_SESSION['last_activity'] = time();
                return true;
            }
        }
        return false;
    }

    // Функция перенаправления пользователя на страницу входа
    function redirectToLogin()
    {
        header("Location: index.php");
        exit;
    }

    function redirectToPage($value)
    {
        header("Location: " . $value);
        exit;
    }    

    // Функция экранирования данных для вывода в HTML
    function escapeHTML($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    // Функция защиты от XSS-атак
    function protectFromXSS($data)
    {
        if (is_array($data)) {
            return array_map('protectFromXSS', $data);
        } else {
            return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        }
    }
?>