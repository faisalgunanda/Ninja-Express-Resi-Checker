<html>
<head>
	<title>Ninja Express Nomor Resi Checker</title>
	<style type="text/css">
		body{
			color:#009900;
			font-family:'Courier'
		}
		textarea{
			color:#009900;
			background:transparent;
			border-color:#009900;
			padding:5px;
		}
		input{
			color:#111111;
			background:#009900;
			margin-top:10px;
			font-size:20px;
			border:2px double #009900;
			padding:2px;
			padding-left:150px;
			padding-right:150px;
			font-family:Arial
		}
	</style>
</head>
<body>
	<center>
		<img src="https://www.ninjavan.co/assets/images/logos/nv-logo-top-id.svg">
		<h1>Ninja Express Nomor Resi Checker</h1>
		<form method="post">
			<textarea id="list" name="list" placeholder="Masukan Nomor Resi Contoh : TSU131106" style="width:700px;height:250px;"></textarea><br>
			<input type="submit" name="check" value="Check"/>
		</form>
	</center>
</body>
</html>
<?php
if(!empty($_POST['check'])) {
	$list = $_POST['list'];
	$kodepengiriman = explode(PHP_EOL, $list);
	echo '<pre>';
	foreach($kodepengiriman as $code) {
		$url = "https://api.ninjavan.co/id/dash/1.2/public/orders?tracking_id=NVIDISBLA".$code;
		$ch = curl_init($url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_TIMEOUT,10);
		$curl = curl_exec($ch);
		$data = json_decode($curl);
		$checkhttpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if($checkhttpcode == "200" && $data->status == "Completed")  {
			echo "[FOUND!]\n";
			echo "Status : $data->status\n";
			echo "Nomor Resi : $data->tracking_id\n";
			echo "Penerima : ". $data->pods[0]->name."\n"; 
		}elseif ($checkhttpcode == "200" && $data->status == "Arrived at" || $httpcode == '200' && $data->status == "On Hold") {
			echo "[FOUND! Status : $data->status] Nomor Resi : $data->tracking_id\n";
		}else {
			echo "<font color='red'>[NOT FOUND! Pesanan Belum Dikirim atau Tidak Ada] Nomor Resi : $code</font>\n";
		}
	}
	echo '</pre>';
}
?>
