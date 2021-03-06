<?php

defined( '_HOMEREXEC' ) or die( 'Restricted access' );

# XX-10272 - required for 32-bit or get error on login
ini_set('mongo.native_long', 0);

require("class/auth/index.php");
require("class/auth/sipx/settings.php");

class HomerAuthentication extends Authentication {
                            
    function login($username, $password) {
      $m = new Mongo(SIPX_DB_URL, array("replicaSet" => "sipxecs"));
      $db = $m->imdb;
      $u = $db->entity->findOne(array("uid" => $username, "pntk" => $password));
      if ($u) {
          if (in_array(SIPX_ADMIN_PERM, $u['prm'])) {
              $_SESSION['loggedin'] = $username;
              $_SESSION['userlevel'] = 1;
              return true;
          }
      }

      return false;
    }
}

?>