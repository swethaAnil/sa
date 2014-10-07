<html>

<script type="text/javascript">

function access_show(obj) {
no = obj.options[obj.selectedIndex].value;
count = obj.options.length;
for(i=1;i<count;i++)
document.getElementById('AccDiv'+i).style.display = 'none';
if(no>0)
document.getElementById('AccDiv'+no).style.display = 'block';
}

function voice_show(obj) {
no = obj.options[obj.selectedIndex].value;
count = obj.options.length;
for(i=1;i<count;i++)
document.getElementById('Div'+i).style.display = 'none';
if(no>0)
document.getElementById('Div'+no).style.display = 'block';
}
</script>

<body>

<h2> SA Config Generator </h2>
<br />

<form name="myForm" action="config_gen_bravo.php" method="POST">

Device Type: 
<select name="device">
<option value=""></option>
<option value="IAD">IAD</option>
<!--<option value="ISR">ISR</option>-->
</select>
<br />

Access Type: 
<select onchange="access_show(this)" name="access">
<option value=""></option>
<!--<option value="1">T1</option>-->
<!--<option value="2">EFM</option>-->
<option value="3">Fiber</option>
</select>
<br />

<div id="AccDiv1" style="display:none">
Number of T1s?
<select name="num_circuits_T1">
<option value=""></option>
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
</select>
</div>

<div id="AccDiv2" style="display:none">
Number of EFM pairs?
<select name="num_circuits_EFM">
<option value=""></option>
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
</select>
</div>

<!--
<div id="AccDiv3" style="display:none">
Fiber service provider?
<select name="fiber_sp">
<option value=""></option>
<option value="ZAYO">ZAYO</option>
</select>
</div>
-->



Primary Call Agent:  
<select name="pca">
<option value=""></option>
<!--<option value="BTS">BTS</option>-->
<option value="BROADSOFT">Broadsoft</option>
</select>
<br />

Voice Service Type: 
<select onchange="voice_show(this)" name="voice_type">
<option value=""></option>
<option value="1">Analog</option>
<!--<option value="2">PRI</option>-->
<!--<option value="3">CAS</option>-->
<!--<option value="4">SIP</option>-->
<!--<option value="5">Voice-only PRI</option>-->
<!--<option value="6">PRI-Mixed</option>-->
<!--<option value="7">CAS-Mixed</option>-->
</select>

<div id="Div1" style="display:none">
Number of analog lines?
<select name="num_analog_lines_ANALOG">
<option value=""></option>
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
<option value="24">24</option>
<option value="25">25</option>
<option value="26">26</option>
<option value="27">27</option>
<option value="28">28</option>
<option value="29">29</option>
<option value="30">30</option>
<option value="31">31</option>
<option value="32">32</option>
<option value="33">33</option>
<option value="34">34</option>
<option value="35">35</option>
<option value="36">36</option>
<option value="37">37</option>
<option value="38">38</option>
<option value="39">39</option>
<option value="40">40</option>
<option value="41">41</option>
<option value="42">42</option>
<option value="43">43</option>
<option value="44">44</option>
<option value="45">45</option>
<option value="46">46</option>
<option value="47">47</option>
<option value="48">48</option>
</select>
</div>

<div id="Div2" style="display:none">
Number of PRI trunk groups?
<select name="num_trunk_groups_PRI">
<option value=""></option>
<option value="1">1</option>
<option value="2">2</option>
</select>
</div>

<div id="Div3" style="display:none">
Number of CAS trunk groups?
<select name="num_trunk_groups_CAS">
<option value=""></option>
<option value="1">1</option>
<option value="2">2</option>
</select>
</div>

<div id="Div4" style="display:none">
Number of analog lines?
<select name="num_analog_lines_SIP">
<option value=""></option>
<option value="0">0</option>
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
</select>
</div>

<div id="Div5" style="display:none">
Number of analog lines?
<select name="num_analog_lines_VOPRI">
<option value=""></option>
<option value="0">0</option>
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
</select>
</div>

<div id="Div6" style="display:none">
Number of PRI trunk groups?
<select name="num_trunk_groups_PRIM">
<option value=""></option>
<option value="1">1</option>
<option value="2">2</option>
</select>
<br />
Number of analog lines?
<select name="num_analog_lines_PRIM">
<option value=""></option>
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
</select>
</div>

<div id="Div7" style="display:none">
Number of CAS trunk groups?
<select name="num_trunk_groups_CASM">
<option value=""></option>
<option value="1">1</option>
<option value="2">2</option>
</select>
<br />
Number of analog lines?
<select name="num_analog_lines_CASM">
<option value=""></option>
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
</select>
</div>
<br />
<input type="submit" value="Submit">

</form>

</body>
</html>
