<?php

/**
 * omen_init() initializes a repository folder and stores its name
 * @param  string $repo_name  The desired path for the repository folder
 * @return bool
 */
function omen_init($repo_name = 'repo')
{
    omen_repo_name($repo_name);
    if (!is_dir($repo_name))
        return mkdir($repo_name);
    return true;
}

/**
 * omen_flush() removes the stored repository folder
 * @return bool
 */
function omen_flush()
{
    $repo_name = omen_repo_name();
    if (is_dir($repo_name))
        return rmdir($repo_name);
    return true;
}

/**
 * omen_repo_name() static storage for the repository folder path
 * @return string
 */
function omen_repo_name($name = null)
{
    static $stored;
    if ($name !== null)
        $stored = $name;
    return $stored;
}

/* --- */

class Status
{
    public $ok;      // bool - whether or not the check returned OK or not
    public $message; // string - the error message to send if not $ok

    public function __construct($ok, $message = '')
    {
        $this->ok = $ok;
        $this->message = $message;
    }
}
class Contact
{
    public $type;    // sms, tts, email
    public $value;   // the actual number or address
    public $minutes; // how many minutes between each alert

    public function __construct($type,$value,$minutes)
    {
        $this->type = $this->verify_type($type);
        $this->value = $value;
        $this->minutes = $minutes;
    }

    private function verify_type($type)
    {
        if (!is_string($type) || !in_array($type, ['sms','tts','email'])) {
            throw new UnexpectedValueException(get_class($this) . ' expects $type to be one of "sms","tts","email"');
        }
    }
}

function omen($callable, Contact $contact)
{
    $status = call_user_func($callable);

    if (!is_object($status) || !($status instanceof Status)) {
        throw new UnexpectedValueException('omen(): $callable should return an instance of Status');
    }

    if ($status->ok) {
        omen_status_clear($callable, $contact);
    } elseif (status_can_send($callable, $contact)) {
        omen_status_send($callable, $contact);
    }
}

//omen_init('last')
//omen('homepage_ok', new Contact('sms', '555-555-1234', 30));

// https://dashboard.nexmo.com/private/dashboard (sms/tts)
// // every check (callable) must return a Status
// // for each check and contact, a file is stored "last/<callable>.<contact.type>.<contact.value>"
// // 1. on every check, if ok=true, the file is emptied
// // 2. if false, a message is sent and the current timestamp is stored in the file
// // 3. for each false check, if the delta between the current time and the stored is greater than the specified minutes, we do #2, otherwise nothing
