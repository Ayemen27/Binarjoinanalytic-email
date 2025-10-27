<?php

namespace App\Models;

use Exception;
use Carbon\Carbon;
use App\Models\Imap;
use Webklex\PHPIMAP\ClientManager;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrashMail extends Model
{

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'email',
        'domain',
        'ip',
        'fingerprint',
        'expire_at'
    ];

    protected $dates = ['deleted_at', 'expire_at'];


    public function deleteOldRecords()
    {
        $recordsToDelete = $this->orderBy('id', 'desc')->skip(100)->get();
        $this->whereNotIn('id', $recordsToDelete->pluck('id'))->delete();
    }


    /*

    public static function connection($mask = false, $imap)
    {

        $cm = new ClientManager(base_path('config/imap.php'));

        $client = $cm->make([
            'protocol'      => 'imap',
            'host' => $imap->host,
            'port' => $imap->port,
            'encryption' => $imap->encryption,
            'validate_cert' => $imap->validate_certificates,
            'username' => $imap->username,
            'password' => $imap->password,
            'authentication' => null,
        ]);

        //$client = Client::account('account');

        $client->connect();

        if ($mask) {
            $message_mask = \Webklex\PHPIMAP\Support\Masks\MessageMask::class;
            $client->setDefaultMessageMask($message_mask);
        }

        return $client;
    }



    public static function allMessages($email, $time = 0)
    {

        $extractEmail = extractEmail($email);

        $domainPrefix = $extractEmail['domainPrefix'];
        $prefix = $extractEmail['prefix'];
        $tag = $extractEmail['tag'];

        $imap = Imap::where('tag', $tag)->first();

        if ($imap === null) {
            return [
                'mailbox' => "Error: No IMAP server found. Please check your email server settings and try again.",
                'messages' => []
            ];
        }

        try {
            $client = TrashMail::connection(true, $imap);
            $folder = $client->getFolderByName('INBOX');

            if ($time == 0) {
                $messages = $folder->query()->to($email)->get();
            } else {
                $messages = $folder->query()->to($email)->since(Carbon::now()->subDays($time)->format('d-M-Y'))->get();
            }

            $response = [
                'mailbox' => $email,
                'messages' => []
            ];

            foreach ($messages as $message) {

                $id = $message->getAttributes()["uid"];
                $hash_id = Hashids::encode($id) . $imap->id;

                $data = [];

                try {
                    $is_seen =  $message->getFlags()['seen'];
                    if ($is_seen == "Seen") {
                        $is_seen = true;
                    } else {
                        $is_seen = false;
                    }
                } catch (\Throwable $th) {
                    $is_seen = false;
                }

                $data['is_seen'] = $is_seen;

                //$data = Cache::remember($hash_id, 10000 * 60, function () use ($message, $hash_id, $id) {

                $from_personal = $message->getAttributes()["from"][0]->personal;
                $from_mail =     $message->getAttributes()["from"][0]->mail;
                $subject =       $message->getAttributes()["subject"][0];
                $date =          new Carbon($message->getAttributes()["date"][0]);
                $date =          $date->format('Y-m-d H:i:s');

                $data['subject'] = $subject;

                $data['from'] = $from_personal;
                $data['from_email'] = $from_mail;
                $data['receivedAt'] = $date;
                $data['id'] = $hash_id;
                $data['html'] = $message->hasHTMLBody();
                $data['attachments'] = [];

                if ($message->hasHTMLBody()) {
                    $data['content'] = str_replace('<a', '<a target="blank"', $message->mask()->getHTMLBodyWithEmbeddedBase64Images());
                } else {
                    $text = $message->getTextBody();
                    $data['content'] = str_replace('<a', '<a target="blank"', str_replace(array("\r\n", "\n"), '<br/>', $text));
                }

                if ($message->hasAttachments()) {

                    $attachments = $message->getAttachments();

                    $directory = './temp/attachments/' . $domainPrefix . '/' . $prefix . '/' . $hash_id . '/';
                    $download = './d/' . $domainPrefix . '/' . $prefix . '/' . $hash_id . '/';
                    is_dir($directory) ?: mkdir($directory, 0777, true);
                    foreach ($attachments as $attachment) {

                        if ($attachment->getAttributes()['disposition'] == "attachment") {
                            $extension = $attachment->getExtension();
                            $allowed = explode(',', getSetting('allowed_files'));
                            $name =  str_replace(' ', '_', basename(htmlspecialchars($attachment->getAttributes()['name'])));

                            if (in_array($extension, $allowed)) {
                                if (!file_exists($directory . $name)) {
                                    $attachment->save($directory, $name);
                                }
                                if ($name !== 'undefined') {
                                    $size = filesize($directory . $name);
                                    $url = url('/') . str_replace('./', '/', $download . $name);
                                    array_push($data['attachments'], [
                                        'name' => $name,
                                        'extension' => $extension,
                                        'size' => $size,
                                        'url' => $url
                                    ]);
                                }
                            }
                        }
                    }
                }
                //   return $data;
                // });


                array_push($response["messages"], $data);
            }
            return $response;
        } catch (Exception $e) {
            $response = [
                'mailbox' => "Erorr :/ , Please try reloading or clearing the cache and try again",
                'messages' => []
            ];
        }
    }





    public static function messages($hash_id)
    {

        try {

            $id = decode_hash(substr($hash_id, 0, 45));
            $imap_id = substr($hash_id, 45);

            $imap = Imap::where('id', $imap_id)->first();

            if ($imap === null) {
                return null;
            }

            if ($id === false) {
                return null;
            }

            $client = TrashMail::connection(true, $imap);
            $folder = $client->getFolderByName('INBOX');
            $message = $folder->query()->getMessageByUid($id);
            //$id = $message->getAttributes()["uid"];
            // $hash_id = Hashids::encode($id);
            $response = [];


            $from_personal = $message->getAttributes()["from"][0]->personal;
            $from_mail =     $message->getAttributes()["from"][0]->mail;
            $subject =       $message->getAttributes()["subject"][0];
            $date =          new Carbon($message->getAttributes()["date"][0]);
            $date =          $date->format('Y-m-d H:i:s');
            $email = $message->getAttributes()["to"][0]->mail;

            $extractEmail = extractEmail($email);

            $domainPrefix = $extractEmail['domainPrefix'];
            $prefix = $extractEmail['prefix'];


            try {
                $message->setFlag('Seen');
                $is_seen = true;
            } catch (\Throwable $th) {
                $is_seen = false;
            }


            $data['subject'] = $subject;
            $data['is_seen'] = $is_seen;
            $data['from'] = $from_personal;
            $data['from_email'] = $from_mail;
            $data['receivedAt'] = $date;
            $data['id'] = $hash_id;
            $data['html'] = $message->hasHTMLBody();
            $data['attachments'] = [];

            if ($message->hasHTMLBody()) {
                $data['content'] = str_replace('<a', '<a target="blank"', $message->mask()->getHTMLBodyWithEmbeddedBase64Images());
            } else {
                $text = $message->getTextBody();
                $data['content'] = str_replace('<a', '<a target="blank"', str_replace(array("\r\n", "\n"), '<br/>', $text));
            }

            if ($message->hasAttachments()) {

                $attachments = $message->getAttachments();

                $directory = './temp/attachments/' . $domainPrefix . '/' . $prefix . '/' . $hash_id . '/';
                $download = './d/' . $domainPrefix . '/' . $prefix . '/' . $hash_id . '/';
                is_dir($directory) ?: mkdir($directory, 0777, true);
                foreach ($attachments as $attachment) {

                    if ($attachment->getAttributes()['disposition'] == "attachment") {
                        $extension = $attachment->getExtension();
                        $allowed = explode(',', getSetting('allowed_files'));
                        $name =  str_replace(' ', '_', basename(htmlspecialchars($attachment->getAttributes()['name'])));

                        if (in_array($extension, $allowed)) {
                            if (!file_exists($directory . $name)) {
                                $attachment->save($directory, $name);
                            }
                            if ($name !== 'undefined') {
                                $size = filesize($directory . $name);
                                $url = url('/') . str_replace('./', '/', $download . $name);
                                array_push($data['attachments'], [
                                    'name' => $name,
                                    'extension' => $extension,
                                    'size' => $size,
                                    'url' => $url
                                ]);
                            }
                        }
                    }
                }
            }

            array_push($response, $data);
            $client->disconnect();
            return $response[0];
        } catch (Exception $e) {
            $response = [
                'message' => $id
            ];
        }
    }




    public static function DeleteMessage($hash_id)
    {
        try {
            $id = decode_hash(substr($hash_id, 0, 32));
            $imap_id = substr($hash_id, 32);

            $imap = Imap::where('id', $imap_id)->first();

            if ($imap === null) {
                return null;
            }

            if ($id === false) {
                return null;
            }

            $client = TrashMail::connection(false, $imap);
            $folder = $client->getFolderByName('INBOX');
            $message = $folder->query()->getMessageByUid($id);
            $message->delete($expunge = true);
            $client->disconnect();
            return true;
        } catch (Exception $e) {
            \abort(404);
        }
    }



    /*public static function SaveMessage($hash_id)
    {
        try {
            $id = decode_hash(substr($hash_id, -32));

            if ($id === false) {
                return null;
            }

            $client = TrashMail::connection();
            $folder = $client->getFolderByName('INBOX');
            $message = $folder->query()->getMessageByUid($id);
            $message->save("qsdqsdqs.eml");
            $client->disconnect();
            return true;
        } catch (Exception $e) {
            \abort(404);
        }
    }
    */
}