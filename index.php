<?php

  $host_thermometer='10.0.0.1';
  $host_emp='10.0.0.2';
  $community_thermometer='public';
  $community_emp='public';

  include("./cfg.php"); // an actual configuration is here

  function snmp_get_and_clear( $host, $com, $oid, $t_out=-1, $ret=-1){
    $rv= snmpget( $host, $com, $oid, $t_out, $ret );
    if ( $rv === False )
      return('unknown');
    if (preg_match('/^[Ii][Nn][Tt][Ee][Gg][Ee][Rr].+\s(\d+)$/', $rv, $p)){
      return($p[1]);
    }
    return('unknown');
  }

  $state_external=snmp_get_and_clear( $host_emp, $community_emp, '.1.3.6.1.4.1.534.1.6.8.1.3.1', 1500, 2);
  if ($state_external != 'unknown'){
    $state_external=$state_external==4?'<font color="green">Ok</font>':'<font color="red">Fail</font>';
  }
  $state_generator=snmp_get_and_clear( $host_emp, $community_emp, '.1.3.6.1.4.1.534.1.6.8.1.3.2', 1500, 2);
  if ($state_generator != 'unknown'){
    $state_generator=$state_generator==4?'<font color="red">Running</font>':'<font color="green">Off</font>';
  }
  $temperature_generator=snmp_get_and_clear( $host_thermometer, $community_thermometer, '.1.3.6.1.4.1.9.9.13.1.3.1.3.2');
  $temperature_servers=snmp_get_and_clear( $host_thermometer, $community_thermometer, '.1.3.6.1.4.1.9.9.13.1.3.1.3.1');

?>
<!DOCTYPE html>
<html>
<head>
  <title>Power status</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="s.css">
</head>
<body>
  <div class="info">
    <div style="color: blue; font-weight: bold;">Main line:&nbsp; <?php echo $state_external; ?></div>
    <div style="color: blue; font-weight: bold; padding-top:10px">Generator:&nbsp; <?php echo $state_generator; ?></div>
    <div style="color: blue; font-weight: bold; padding-top:10px">Gen. temp.:&nbsp; <?php echo $temperature_generator; ?></div>
    <div style="color: blue; font-weight: bold; padding-top:10px">Serv. temp.:&nbsp; <?php echo $temperature_servers; ?></div>
    <div style="color: red; padding-top:10px">Time:&nbsp; <?php echo strftime("%T") ; ?></div>
    <div style="padding-top:30px"><a href="index.php">Оновити</a></div>
  </div>
</body>
</html>
