<html>
<head>
	<title>Sample CKEditor Site</title>
	<script  type="text/javascript" src="ckeditor/ckeditor.js"></script>
</head>
<body>
	<form method="post">
		<p>
			My Editor:<br />
			<textarea id="editor1" name="editor1">&lt;p&gt;Initial value.&lt;/p&gt;</textarea>
                        <textarea id="editor2" name="editor1">&lt;p&gt;Initial value.&lt;/p&gt;</textarea>
			<script type="text/javascript">
				CKEDITOR.replace( 'editor1' );
                                CKEDITOR.replace( 'editor2' );
			</script>
		</p>
		<p>
			<input type="submit" />
		</p>
	</form>
</body>
</html>