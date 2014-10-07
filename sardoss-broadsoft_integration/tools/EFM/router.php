
<?

Class Router
{

function Router()
{
$this->connesso=0;
}

function connect($ServerAddress, $errno, $errstr, $cfgPort=23,$cfgTimeOut=10)
{
$this->stream= fsockopen($ServerAddress, $cfgPort, $errno, $errstr, $cfgTimeOut);
if(!$this->stream)
{
$this->connesso=0;
}
else
{
$this->connesso=1;
}
}

function disconnect()
{
if($this->connesso==0){exit();}
fwrite ($this->stream, "lo\r\n");
fclose($this->stream);
$this->connesso=0;
}

function login ($login,$password)
{
if($this->connesso==0){exit();}

if(strlen($login)>0)
{
fputs ($this->stream, "$login\r\n");
fputs ($this->stream, "$password\r\n");
}
else
{
fputs ($this->stream, "$password\r\n");
}

stream_set_timeout($this->stream, 2);

fputs ($this->stream,"terminal length 0\r\n");
/*
if I send a list of commands here it get the command and i see the output
in next read of strream, for example:
fputs ($this->stream,"sho int desc\r\n"); fputs
($this->stream,"sho ver\r\n");
....
*/

}


function runCommand($command,$logoutput=0,$ntimeout=10)
{

if(strlen($command)>0)
{
fputs ($this->stream, $command."\r\n");
}


$output=array();
while(!feof($this->stream))
{
$info = stream_get_meta_data($this->stream);
if ($info['timed_out'])
{
$ntimeout--;
usleep(100000);
}
else
{
$line=fread($this->stream,1000);
$output[]=$line;
}
if ($ntimeout==0)
{
break;
}
}
return $output;
}



}//class Router
?>