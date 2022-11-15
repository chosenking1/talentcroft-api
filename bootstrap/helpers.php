<?php
/**
 * Gets authenticated user
 * @return \Illuminate\Contracts\Auth\Authenticatable|User
 */
const PHP_SOL = '<?php';
// const AMPER_DOUBLE_CURLY_OPEN = '@{{';
const DOUBLE_CURLY_OPEN = '{{';
const DOUBLE_CURLY_CLOSE = '}}';

const ESCAPE_OPEN = '{!!';
const ESCAPE_CLOSE = '!!}';
function getUser()
{
    return auth()->user();
}

function linkActive($route): string
{
    return request()->routeIs($route) ? 'active' : '';
}

function showActive($route): string
{
    return request()->routeIs($route) ? 'show' : '';
}

function array_random($arr, $num = 1)
{
    shuffle($arr);

    $r = array();
    for ($i = 0; $i < $num; $i++) {
        $r[] = $arr[$i];
    }
    return $num == 1 ? $r[0] : $r;
}

function nf($num = 0, $d = 2)
{
    return number_format($num, $d);
}

/**
 * @param $file
 * @param string $path
 * @return string
 */
function moveFile($file, $path = 'uploads'): string
{
    try {
        if (!$file || !$file->isValid()) {
            return asset('6.jpg');
        }
        $s = cloudinary()->upload($file->getRealPath(), ['folder' => "getgrid/$path", 'use_filename' => true]);
        return $s->getSecurePath();
    } catch (\Exception $e) {
        return asset('6.jpg');
    }
}

function isLocalhost($whitelist = ['127.0.0.1', '::1', 'localhost', ':8000'])
{
    return in_array($_SERVER['REMOTE_ADDR'], $whitelist);
}

/**
 * If the given value is not an array and not null, wrap it in one.
 *
 * @param mixed $value
 * @return array
 */
function array_wrap($value): array
{
    if (is_null($value)) {
        return [];
    }
    return is_array($value) ? $value : [$value];
}

function getDatesFromRange($start, $end, $format = 'Y-m-d')
{
    $array = array();
    $interval = new DateInterval('P1D');
    try {
        $realEnd = new DateTime($end);
        $realEnd->add($interval);
        $period = new DatePeriod(new DateTime($start), $interval, $realEnd);
    } catch (Exception $e) {
        $period = [];
    }
    foreach ($period as $date) {
        $array[] = $format ? $date->format($format) : new \Carbon\Carbon($date);
    }
    return $array;
}

function showSearchHighlight($text)
{
    $search = request()->search;
    return str($text)->replace($search, "<b class='text-white bg-black p-1'>$search</b>");
}

/**
 * Notify admin about issues
 * @param mixed $data
 */
function notifyAdmin(mixed $data)
{
    if (!is_array($data)){
        $data = [];
        $data = ['subject' => 'Urgent Attention'];
        $data['message'] = $message;
    }
    Mail::send('emails.mailer', compact('data'), function ($message) use ($data) {
        $message->from('grid@talentx.africa', 'GetGrid');
        $message->to('idowu.immanuel17@gmail.com', 'Immanuel Idowu');
        $message->cc('dejiomoloja@gmail.com', 'Deji Omoloja');
        $message->subject($data['subject']);
    });
}

function sendSlackMessage()
{

}
