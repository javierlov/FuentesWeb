<html>
<head>
<script language="javascript">
function WriteToFile()
{
var fso = new ActiveXObject("Scripting.FileSystemObject");
var s = fso.CreateTextFile("C:\\Test.txt", true);
s.WriteLine('Hello');
s.Close();
}
</script>
</head>
<body onLoad="WriteToFile()">

</body>
</html>