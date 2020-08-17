<?php
include('../core/db.php');
include('Config.php');
include('DB.php');
include('User.php');
include('Session.php');
include('../functions/helpers.php');

class Search
{
  public $_db;

  public function __construct()
  {
    $this->_db = DB::getInstance();
  }

  public function get($query, $requested_at)
  {
    if ($query) {
      $search = $this->_db->query("SELECT id FROM tickets WHERE subject LIKE '%{$query}%' OR content LIKE '%{$query}%'");

      if ($search->count() > 0) {
        header('Content-Type: application/json');
        echo json_encode(
          (object) [
            'results' => $this->prepare($search->results()),
            'requested_at' => $requested_at
          ]
        );
      }
    } else {
      return;
    }
  }

  private function prepare($ids)
  {
    if (!$ids) {
      return false;
    }
    
    $parsed_posts = [];

    foreach ($ids as $id) {
      $id = $id->id;

      $sql = $this->_db->get('tickets', ['id', '=', $id]);

      if ($sql->count() > 0) {

        $results = $sql->results();


        foreach ($results as $result) {
          $parsed_posts[] = $this->getHtml($result);
        }

      } else {
        return $sql->error();
      }
    }

    return $parsed_posts;
  }

  private function getHtml($parsed_post)
  {
    $user = new User();
    $today = date_create(date('Y-m-d H:i:s'));
    $ticket_date = date_create($parsed_post->created);
    $diff = date_diff($today, $ticket_date);

    switch ($parsed_post->priority) {
      case 'no':
        $priority =  "<span class='label warning'>No</span>";
        break;

      case 'yes':
        $priority =  "<span class='label alert'>Yes</span>";
        break;
    }

    switch ($parsed_post->status) {
      case 'open':
        $status = "<span class='label success'>Open</span>";
        break;

      case 'closed':
      case 'close':
        $status = "<span class='label alert'>Closed</span>";
        break;
    }

    return '
      <tr>
        <td>' . $parsed_post->id . '</td>
        <td>' . $parsed_post->ticket_id . '</td>
        <td>' . $parsed_post->subject . '</td>
        <td>' . show_max_char_length($parsed_post->content, 30) . '</td>
        <td>' . $parsed_post->subject . '</td>
        <td>' . $user->find((int) $parsed_post->user_id)->nick_name . '</td>
        <td>' . calc_date_diff($diff) . '</td>
        <td>' . $priority . '</td>
        <td>' . $status . '</td>
        <td colspan="2"><a class="button tiny primary" href="view-ticket.php?id=' . $parsed_post->ticket_id . '">View</a></td>
      </tr>
    ';
  }
}

if ($_POST["action"] == "Load") {
  $search = new Search();
  $search->get($_POST['s'], $_POST['requestedAt']);
}
