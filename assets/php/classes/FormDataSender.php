<?php 

/**
 * Отправляет данные, переданные из формы в телеграм, по почте и в битрикс, 
 проверяет на спам
 */
class FormDataSender
{
    private $phone; //телефон из формы
    private $name;  //имя из формы
    private $form;  //название формы
    private $title; // заголовок страницы, с которой отправили форму
    private $telegram_token   = "961313657:AAGAMoIvveEHv3GiEC_Sed4uXByUPvLZXiA";
    private $telegram_chat_id = "-322308753";
    private $admin_email      = "aner-anton@ya.ru";
    private $client_email     = "atomprint@yandex.ru";
    private $bitrix_url       = 
    "https://b24-kc1wpe.bitrix24.ru/rest/1/nb0pdgtxml39hy2t/crm.lead.add.json";


    public function __construct(array $data)
    {
        if (!empty($data['phone'])) {
            $this->phone = $this->preparePhone($data['phone']);
        } else throw new Exception("Не передан номер телефона");

        if (!empty($data['name']) && trim($data['name']) != false) {
            $this->name = $this->prepareName($data['name']);
        } else $this->name = "Не указано";

        if (!empty($data['form-name'])) {
            $this->form = $data['form-name'];
        } else $this->form = "Не указано";

        if (!empty($data['page-title'])) {
            $this->title = $data['page-title'];
        } else $this->title = "Нет данных";        
    }

    public function checkSpam()
    {
        return $this->title === 'Нет данных' ? true : false;
    }

    public function sendToTelegram()
    {
        // Готовим данные к отправке
        $arr = [
            "Имя: "      => $this->name,
            "Телефон: "  => $this->phone,
            "Страница: " => $this->title,
            "Форма: "    => $this->form,
        ];

        $txt = "";
        foreach ($arr as $key => $value) {
            $txt .= "<b>" . $key . "</b> " . $value . "\n";
        }

        // Отправляем данные
        $result = file_get_contents("https://api.telegram.org/bot" . 
        $this->telegram_token . "/sendMessage?chat_id=" . 
        $this->telegram_chat_id . "&parse_mode=html&text=" . urlencode($txt));
        
        // Проверяем результат
        $result = json_decode($result, 1);
        if ($result['ok']) return true;
        else return false;
    }

    public function sendToBitrix()
    {
        $data = http_build_query(array(
            'fields' => array(
                'TITLE'     => "Заявка с сайта",
                'NAME'      => $this->name,
                'COMMENTS'  => "Страница: " . $this->title,
                'SOURCE_ID' => 'Сайт',
                'WEB'       => $this->title,
                'PHONE'     => Array(
                    
                    'n0' => [
                        'VALUE'      => $this->phone,
                        'VALUE_TYPE' => 'WORK',
                    ],
                ),
            ),
            'params' => ["REGISTER_SONET_EVENT" => "Y"]
            ));

        // отправляем в битрикс 
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POST           => 1,
            CURLOPT_HEADER         => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL            => $this->bitrix_url,
            CURLOPT_POSTFIELDS     => $data,
        ));
        $result = curl_exec($curl);
        curl_close($curl);

        // Проверяем результат
        $result = json_decode($result, 1);
        if (array_key_exists('error', $result)) {
            mail($this->admin_email, 'Ошибка при сохранении лида в битрикс', 
            "Ошибка при сохранении лида: " . $result['error_description']);
            return false;
        } else return true;
    }

    public function sendEmails()
    {
        $subject = 'НОВАЯ ЗАЯВКА!';
        $body = PHP_EOL . "Имя: " . $this->name . PHP_EOL . "Телефон: " . 
        $this->phone . PHP_EOL . "Страница: " . $this->title . PHP_EOL . 
        "Форма: " . $this->form;
        $result = mail($this->admin_email, $subject, $body);
    }

    public function getPhone()
    {
        return $this->phone;
    }

    protected function preparePhone($phone)
    {
        return htmlspecialchars(strip_tags($phone));
    }

    protected function prepareName($name)
    {
        $name = explode(" ", $name);
        $name = array_reduce($name, 
            function ($carry, $item) {
                if (!empty($item)) {
                    $item = $this->mb_ucfirst($item);
                    return $carry . " " . $item;
                } else return $carry;
            }
        );
        return htmlspecialchars(strip_tags(trim($name)));
    }  

    protected function mb_ucfirst($str)
    {
        $first = mb_substr($str, 0, 1);
        $tail = mb_substr($str, 1);
        return mb_strtoupper($first) . mb_strtolower($tail);
    }  
}
