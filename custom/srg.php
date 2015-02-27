<?php

require 'ZabbixApiAbstract.class.php';
require 'ZabbixApi.class.php';

try {
    $forquery = array();
    $qery = array();
    $jsla = array();
    $hostgroups = array();
    $api = new ZabbixApi('https://localhost/zabbix/api_jsonrpc.php', 'dashboard', 'dwnxo3C1fX');
    $services = $api->serviceGet(array('output' => 'extend','selectDependencies' => 'extend'));

    foreach($services as $service) {
        $servnames[$service->serviceid] = $service->name;
        foreach($service->dependencies as $dependency) {
           $noroot[$dependency->servicedownid]=1;
        }
    }
    foreach($services as $service) {
        if (!isset($noroot[$service->serviceid])) {
           array_push($forquery, $service->serviceid);
           array_push($hostgroups, $service->name);
           foreach($service->dependencies as $dependency) {
              array_push($forquery, $dependency->servicedownid);
           }
        }
    }
    $slas = $api->serviceGetsla(array('serviceids' => $forquery,
                                      'intervals' => array( 'from' => strtotime("first day of this month 00:00:00"),
                                                                  'to' => strtotime("now"))
                                     )
                               );
    $groups = $api->hostgroupGet(array('output' => 'extend','filter' => array( 'name' => $hostgroups)));

    foreach($services as $service) {
        $itemids = array();
 $hostids = array();
        $groupid = "0";
        foreach($groups as $group) { if ( $group->name == $service->name ) { $groupid = $group->groupid; } };
        $disaster = 0;
        $warning = 0;
        $informational = 0;
        if (!isset($noroot[$service->serviceid])) {
           $childs = array();
           $object['id'] = $service->serviceid;
           $object['name'] = $service->name;
           $items = $api->itemGet(array("output" => "extend", 'groupids' => array($groupid), 'webitems' => 'true', 'filter' => array('state' => '0', 'status' => '0')));
           #var_dump($items);
           $hosts = $api->hostGet(array("output" => "extend", 'groupids' => array($groupid), 'filter' => array('status' => '0')));
           #var_dump($hosts);
           foreach ($items as $item) {
             array_push($itemids, $item->itemid );
           }
           foreach ($hosts as $host) {
             array_push($hostids, $host->hostid );
           }
           $triggers = $api->triggerGet(array("output" => "extend", 'hostids' => $hostids, 'groupids' => array($groupid),'itemids' => $itemids, 'filter' =>array('value' => '1', 'status' => '0', 'state' => '0')));
           #var_dump($triggers);
           foreach($triggers as $trigger) {
             if ($trigger->priority > 4) {$disaster++;}
             if ($trigger->priority >= 3 and $trigger->priority <= 4 ) {$warning++;}
             if ($trigger->priority <= 2 ) {$informational++;}
           }
           $object['url'] = "tr_status.php?&groupid=$groupid";
//           echo "$service->name\n";
//           echo "dis: $disaster\n";
//           echo "wrn: $warning\n";
//           echo "inf: $informational\n";
           if ($disaster > 0) { $object['traffic'] = 3; }
           elseif ($warning > 0) { $object['traffic'] = 2; }
           elseif ($informational > 0) { $object['traffic'] = 1; }
           else { $object['traffic'] = 1; }
//           echo "traffic: ".$object['traffic']."\n";
//           var_dump($triggers);
//           echo "\n-------------------------------------------------------------------------------------------\n";
           $object['startmounth'] = strtotime("first day of this month 00:00:00");
           $object['now'] = strtotime("now");
           $object['sla_mounth'] = $slas->{$service->serviceid}->sla[0]->sla;
 $object['value'] = 100 - (100 - $slas->{$service->serviceid}->sla[0]->sla) * (strtotime("now") - strtotime("first day of this month 00:00:00"))/(strtotime("last day of this month 23:59:00") - strtotime("first day of this month 00:00:00"));#           foreach($service->dependencies as $dependency) {
#              $child['id'] = $dependency->servicedownid;
  $child['name'] = $servnames[$dependency->servicedownid];
#              $child['value'] = $slas->{$dependency->servicedownid}->sla[0]->sla;
#              array_push($childs, $child);
#           }
#           $object['childs'] = $childs;
           array_push($jsla, $object);
        }
    }
#    var_dump($jsla);
    file_put_contents('srg.json', json_encode($jsla));
	//echo "tst";
} catch(Exception $e) {

    // Exception in ZabbixApi catched
    echo $e->getMessage();

}
?>

