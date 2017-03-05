<?php

class ModelChat extends Model
{
    function __construct()
    {
        $this->connect_db();
    }

    const LIMIT_MESSAGES = 25;

    public function checkMessages($user_id, $type, $companionId = false)
    {
        $return = [
            'total_count_new' => 0,
            'users' => [],
            'dialog' => []
        ];

        switch ($type) {

            case 'count':
                $count = $this->getFirst("SELECT COUNT(id) as count FROM chat WHERE to_user_id = $user_id AND is_new = 1");
                $return['total_count_new'] = $count['count'];
                break;

            case 'count_users':

                $newUsersMessages = $this->getAssoc("SELECT 
                  roles.name AS role_name, CONCAT(users.first_name, ' ', users.last_name) AS name,
                  COUNT(chat.id) AS count_new, users.user_id AS user_id, avatar_url AS avatar
                  FROM users 
                  LEFT JOIN roles ON roles.role_id = users.role_id 
                  LEFT JOIN chat ON (chat.user_id = users.user_id AND chat.to_user_id = $user_id AND chat.is_new = 1) 
                  WHERE users.user_id <> $user_id
                  GROUP BY users.user_id
                  ORDER BY chat.message_date ASC");
                if (!empty($newUsersMessages)) {
                    foreach ($newUsersMessages as $newUsersMessage) {
                        $return['total_count_new'] += $newUsersMessage['count_new'];
                    }
                    $return['users'] = $newUsersMessages;
                }
                break;

            case 'user_chat':
                $count = $this->getFirst("SELECT COUNT(id) as count FROM chat WHERE to_user_id = $user_id AND is_new = 1");
                $dialog = $this->getAssoc("SELECT 
                  CONCAT(users.first_name, ' ', users.last_name) AS name, 
                  CONCAT(DATE_FORMAT(chat.message_date ,'%m-%d-%Y %T')) as time,
                  CONCAT(IF(chat.user_id = $user_id, 'out', 'in')) AS dir, avatar_url AS avatar,
                  chat.message_text AS message, chat.id AS id
                  FROM chat 
                  LEFT JOIN users ON (chat.user_id = users.user_id)
                  WHERE (chat.user_id = $companionId AND chat.to_user_id = $user_id) OR 
                    (chat.user_id = $user_id AND chat.to_user_id = $companionId) 
                  GROUP BY chat.id
                  ORDER BY chat.id DESC 
                  LIMIT ".static::LIMIT_MESSAGES);
                $return['total_count_new'] = $count['count'];
                $return['dialog'] = array_reverse($dialog);
                break;
        }

        return json_encode($return);

    }

    public function sendMessage($userId, $companionId, $message)
    {

        $text = $this->safe_var($message);
        $messageId = $this->insert("INSERT INTO chat (user_id, message_text, to_user_id) VALUES 
            ($userId, '$text', $companionId)");

        $dialog['dir'] = 'out';
        $dialog['time'] = '' . date('m-d-Y H:i:s');
        $dialog['message'] = $message;
        $dialog['id'] = $messageId;

        return json_encode($dialog);
    }

    public function readMessages($userId, $companionId)
    {

        $this->update("UPDATE chat SET is_new = 0 WHERE user_id = $companionId AND to_user_id = $userId");

    }

    // функция для обработки переменных, защита от XSS и SQL-инъекций
    private function safe_var ($var)
    {
        $var = trim($var);
        $var = mysql_real_escape_string($var);
        $var = htmlspecialchars($var);
        return $var;
    }


}