<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<head> 
<title> Reset LDAP Password</title> 
<style type="text/css"> 
dt{ 
   font-weight: bold; 
} 
</style> 
</head> 
<body> 

<?php 
/* 
*PHP写的修改LDAP密码的小页面，要先通过用户cn获取到dn，然后通过dn修改用户的密码。
*/ 
 
if(isset($_REQUEST) && array_key_exists('an',$_REQUEST)){ 
$u  = $_REQUEST['u'];  //用户名 
$op = $_REQUEST['op']; //旧密码 
$np1 = $_REQUEST['np1']; 
$np2 = $_REQUEST['np2']; 
$an = $_REQUEST['an']; 
} 
 
if(!empty($an)){ 
   if( empty($u) or empty($op) or empty($np1) or empty($np2) ){ 
       $msg = "Some filed was empty!"; 
   }else{ 
       if( $np1 != $np2 ){ 
           $msg = "新密码输入不一致!"; 
       }else{ 
           if($op == $np1){ 
              $msg = "新密码与旧密码相同,请重新输入!"; 
           }else{ 
               $ldap_host = "ldap-server"; 
               $ldap_port = 389; 
               $base_dn  = "dc=unixfbi,dc=com"; 
               $connect = ldap_connect( $ldap_host, $ldap_port);             //连接服务器 
  if(!$connect){ 
                   $msg = "无法连接LDAP服务器"; 
               }else{ 
                         $user_pass = $op; 
                         ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, 3); 
                         ldap_set_option($connect, LDAP_OPT_REFERRALS, 0); 
                         $uid=sprintf("uid=%s",$u); 
                         $search=ldap_search($connect,$base_dn,$uid);  //根据uid获取到用户的信息 
                         $dn=ldap_get_entries($connect,$search); 
                         for ($i=0; $i<$dn["count"]; $i++)              //从获取到的数组取出用户dn，没有用户dn修改不了密码。
                         { 
                              $user_dn= $dn[$i]["dn"]; 
                         } 
                         $bind = ldap_bind($connect, $user_dn, $user_pass);       //登录验证 
                        if(!$bind){ 
                              $msg = "旧密码不正确,请重新输入!"; 
                         }else{ 
                                 $values["userPassword"][0] = "{SHA}".base64_encode(pack("H*",sha1($np1)));  //密码sha1加密 
                                 $rs = ldap_mod_replace($connect,$user_dn,$values);           //更新用户信息 
                                if($rs){ 
                                         $msg="修改成功!"; 
                                 }else{ 
                                         $msg = "修改失败，请与XX联系!"; 
                                      } 
                              } 
  
                    } 
                 ldap_close($connect);                                                      //关闭连接 
                 } 
            } 
      } 
} 
  
if(!empty($msg)){ 
   print("<h1>$msg</h1>"); 
} ?> 
  
<form  method="post" action=""> 
 <dl> 
   <dt>用户名</dt> 
 <dd><input type="text" name="u"  /></dd> 
  
   <dt>原密码</dt> 
   <dd><input type="password" name="op" /></dd> 
  
   <dt>新密码</dt> 
   <dd><input type="password" name="np1" /></dd> 
  
   <dt>确认新密码</dt> 
   <dd><input type="password" name="np2" /></dd> 
   <dd><input type="submit" value="确定" /></dd> 
 </dl> 
 <input type="hidden" name="an" value="submit" /> 
</form> 
</body> 
</html> 
