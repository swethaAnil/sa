<?php

//define constants

$device_array = array(
				'DEVC1' => 'IAD',
				'DEVC2' => 'SPIAD',
				'DEVC3' => 'ISR',
				'SCDV1' => 'IAD',
				'SCDV2' => 'SPIAD',
				'SCDV3' => 'ISR'
				);

$access_array = array(
				'ACCE1' => 'T1',
				'ACCE2' => 'EFM',
				'ACCE3' => 'FIBER'
				);

$pca_array = array(
				'PRCA1' => 'BTS',
				'PRCA2' => 'BROADSOFT',
				);
				
$voice_array = array(
				'VOIC1' => 'ANALOG',
				'VOIC2' => 'PRI',
				'VOIC3' => 'CAS',
				'VOIC4' => 'SIP',
				'VOIC5' => 'VOPRI',
				'VOIC6' => 'PRIMIX',
				'VOIC7' => 'TCPS'
				);

$time_zone = array(
				'ATL' => 'EST -5',
				'BOS' => 'EST -5',
				'CHI' => 'CST -6',
				'DAL' => 'CST -6',
				'MIA' => 'EST -5',
				'DEN' => 'MST -7',
				'HOU' => 'CST -6',
				'MSP' => 'CST -6',
				'DET' => 'EST -5',
				'SFO' => 'PST -8',
				'LAX' => 'PST -8',
				'SEA' => 'PST -8',
				'SDG' => 'PST -8',
				'DCA' => 'EST -5'
				);

$daylight_savings = array(
				'ATL' => "EDT",
				'BOS' => "EDT",
				'CHI' => "CDT",
				'DAL' => "CDT",
				'MIA' => "EDT",
				'DEN' => "MDT",
				'HOU' => "CDT",
				'MSP' => "CDT",
				'DET' => "EDT",
				'SFO' => "PDT",
				'LAX' => "PDT",
				'SEA' => "PDT",
				'SDG' => "PDT",
				'DCA' => "EDT"
				);
				
$primary_dns = array(
				'ATL' => '64.238.96.12',
				'BOS' => '64.238.96.12',
				'CHI' => '64.238.96.12',
				'DAL' => '66.180.96.12',
				'MIA' => '64.238.96.12',
				'DEN' => '66.180.96.12',
				'HOU' => '66.180.96.12',
				'MSP' => '64.238.96.12',
				'DET' => '64.238.96.12',
				'SFO' => '66.180.96.12',
				'LAX' => '66.180.96.12',
				'SEA' => '66.180.96.12',
				'SDG' => '66.180.96.12',
				'DCA' => '64.238.96.12'
				);
				
$secondary_dns = array(
				'ATL' => '66.180.96.12',
				'BOS' => '66.180.96.12',
				'CHI' => '66.180.96.12',
				'DAL' => '64.238.96.12',
				'MIA' => '66.180.96.12',
				'DEN' => '64.238.96.12',
				'HOU' => '64.238.96.12',
				'MSP' => '66.180.96.12',
				'DET' => '66.180.96.12',
				'SFO' => '64.238.96.12',
				'LAX' => '64.238.96.12',
				'SEA' => '64.238.96.12',
				'SDG' => '64.238.96.12',
				'DCA' => '66.180.96.12'
				);

$voice_port_address = array(
				'IAD' => '2/',
				'SPIAD' => '1/0/',
				'SPIAD_ALT' => '0/2/',
				'ISR' => '2/0/'
				);

$efm_interface = array(
				'IAD' => 'FastEthernet0/0',
				'SPIAD' => 'GigabitEthernet0/0',
				'ISR' => 'GigabitEthernet0/0'
				);					
				
		
$fiber_interface = array(
				'IAD' => 'FastEthernet0/1',
				'SPIAD' => 'GigabitEthernet0/1',
				'ISR' => 'GigabitEthernet0/1'
				);				

$data_interface = array(
				'IAD' => 'FastEthernet0/0',
				'SPIAD' => 'GigabitEthernet0/0',
				'ISR' => 'GigabitEthernet0/0'
				);	

$sip_acl_ip_outbound = array(
				'ATL' => 'permit ip any 74.7.244.0 0.0.0.15',
				'BOS' => 'permit ip any 173.200.255.0 0.0.0.15',
				'CHI' => 'permit ip any 69.198.65.0 0.0.0.15',
				'DAL' => 'permit ip any 72.16.239.80 0.0.0.15<br>permit ip any 74.7.100.0 0.0.0.15',
				'MIA' => 'permit ip any 69.198.137.0 0.0.0.15',
				'DEN' => 'permit ip any 69.198.37.0 0.0.0.15',
				'HOU' => 'permit ip any 69.198.50.0 0.0.0.15',
				'MSP' => 'permit ip any 69.198.151.0 0.0.0.15',
				'DET' => 'permit ip any 69.198.115.0 0.0.0.15',
				'SFO' => 'permit ip any 69.198.130.0 0.0.0.15',
				'LAX' => 'permit ip any 69.198.83.0 0.0.0.15',
				'SEA' => 'permit ip any 69.198.255.0 0.0.0.15',
				'SDG' => 'permit ip any 69.198.98.0 0.0.0.15',
				'DCA' => 'permit ip any 69.198.159.0 0.0.0.15'
				);

				
$sip_acl_ip_inbound = array(
				'ATL' => 'permit ip 74.7.244.0 0.0.0.15 any',
				'BOS' => 'permit ip 173.200.255.0 0.0.0.15 any',
				'CHI' => 'permit ip 69.198.65.0 0.0.0.15 any',
				'DAL' => 'permit ip 72.16.239.80 0.0.0.15 any<br>permit ip 74.7.100.0 0.0.0.15 any',
				'MIA' => 'permit ip 69.198.137.0 0.0.0.15 any',
				'DEN' => 'permit ip 69.198.37.0 0.0.0.15 any',
				'HOU' => 'permit ip 69.198.50.0 0.0.0.15 any',
				'MSP' => 'permit ip 69.198.151.0 0.0.0.15 any',
				'DET' => 'permit ip 69.198.115.0 0.0.0.15 any',
				'SFO' => 'permit ip 69.198.130.0 0.0.0.15 any',
				'LAX' => 'permit ip 69.198.83.0 0.0.0.15 any',
				'SEA' => 'permit ip 69.198.255.0 0.0.0.15 any',
				'SDG' => 'permit ip 69.198.98.0 0.0.0.15 any',
				'DCA' => 'permit ip 69.198.159.0 0.0.0.15 any'
				);

$dark_fiber_10K_interfaces = array(
				'ASR00CNA' => 'GigabitEthernet0/0/2',
				'ASR00BAY' => 'GigabitEthernet0/0/0',
				'ASR00DEN' => 'GigabitEthernet0/3/1',
				'ASR01DEN' => 'GigabitEthernet0/3/1',
				'ASR00DAL' => 'GigabitEthernet0/0/4',
				'ASR01DAL' => 'GigabitEthernet0/0/4'				
				);

$dark_fiber_10K_pairs = array(
				'ATL' => array('ASR00CNA','ASR00BAY'),
				'DAL' => array('ASR00DAL','ASR01DAL'),
				'DEN' => array('ASR00DEN','ASR01DEN'),
				'HOU' => array('ASR00HOU','ASR01HOU')
				);

			
?>


<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-39762922-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>



