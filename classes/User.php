<?php
/**
 * User class
 * @version 0.1
 */
class User
{
  private $_db;
  private $_data;
  private $session_name;
  private $_cookie_name;
  private $_isLoggedIn;

  public function __construct($user = null)
  {
    $this->_db = DB::getInstance();
    $this->session_name = Config::get('session/session_name');
    $this->_cookie_name = Config::get('remember/cookie_name');

    if(!$user) {
      if(Session::exists($this->session_name)) {
        $user = Session::get($this->session_name);
        
        if($this->find($user)) {
          $this->_isLoggedIn = true;
        } else {
          // process logout
        }
      }
    } else {
      $this->find($user);
    }
  }

  /**
  * Create User
  */
  public function create($fields = [])
  {
    if(!$this->_db->insert('users', $fields)) {
      throw new Exception("Problem creating an account.");
    }
  }

  public function find($user = null)
  {
    if($user) {
      $field = (is_numeric($user)) ? 'id' : 'username';
      
      $data = $this->_db->get('users', [$field, '=', $user]);
      
      if($data->count()) {
        $this->_data = $data->first();
        return $this->_data;
      }
    }

    return false;
  }

  public function findByToken($token)
  {
    if($token) {
      
      $data = $this->_db->get('users', ['token', '=', $token]);
      
      if($data->count()) {
        $this->_data = $data->first();
        return $this->_data;
      }
    }

    return false;
  }

  /**
   * Check if user is in db
   */
  public function login($username = null, $password = null, $remember = false)
  {  
    
    if(!$username && !$password && $this->exists()) {

      Session::put($this->session_name, $this->data()->id);
      
    } else { 
      $user = $this->find($username);
      
      if($user) {
        if($this->data()->password === Hash::make($password, $this->data()->salt)) {
          Session::put($this->session_name, $this->data()->id);

          if ($remember == true) {
            $hash = Hash::unique();
            $hash_check = $this->_db->get('users_session', ['user_id', '=', $this->data()->id]);
          

            if(!$hash_check->count()) {

              $this->_db->insert('users_session',[
                'user_id' => $this->data()->id,
                'hash'    => $hash
              ]);

            } else {
              $hash = $hash_check->first()->hash;
            }

            Cookie::put($this->_cookie_name, $hash, Config::get('remember/cookie_expiry'));
          }
          
          return true;
        }
      }
    }
    
    return false;
  }

  public function update($fields = [], $id = null) 
  {

    if(!$id && $this->isLoggedIn()) {
      $id = $this->data()->id;
    }

    if(!$this->_db->update('users', $id, $fields)) {
      throw new Exception('There was a error updating your details');
    }
  }

  public function data()
  {
    return $this->_data;
  }

  /**
   * Check on user permission
   * @param mixed  
   * @return bool|int true|false
   */
  public function hasPermission($key)
  {
    $group = $this->_db->get('users_group', ['id', '=', $this->data()->user_group_id]);
    if($group->count()) {
      $permissions = $group->first()->permissions;
      $permissions = json_decode($permissions, true);
      
      if($permissions[$key] == true) {
        return true;
      } 
    }

    return false;
  }
  
  public function exists()
  {
    return (!empty($this->_data)) ? true : false;
  }

  public function isLoggedIn()
  {
    return $this->_isLoggedIn;
  }

  public function logout()
  {
    $this->_db->delete('users_session', ['user_id', '=', $this->data()->id]);
    Session::delete($this->session_name);
    Cookie::delete($this->_cookie_name);
  }
}
