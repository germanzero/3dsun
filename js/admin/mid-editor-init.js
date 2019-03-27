tinymce.init({
    selector: '.mid-editor',
    height: 500,
    menubar: false,
    plugins: [
      'advlist autolink lists link image charmap print preview anchor textcolor',
      'searchreplace visualblocks code fullscreen',
      'insertdatetime media table contextmenu paste code help wordcount'
    ],
    toolbar: 'insert | undo redo | formatselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
    content_css: [
      '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
      '//www.sun3dcorporation.com/css/3dsun.css'],
    style_formats: [
        { title: 'Title', inline: 'h1', classes: 'title' },
        { title: 'Subtitle', inline: 'h3', classes: 'subtitle' },
        { title: 'Paragraf', selector: 'p', inline: 'p', classes: 'paragraf' },
        { title: 'Bold text', inline: 'strong' },
        { title: 'White text', inline: 'span', classes: 't-w' },
        { title: 'Gray text', inline: 'span', classes: 't-g' },
        { title: 'Yellow text', inline: 'span', classes: 't-y' }
        
    ]
  });

