$(document).ready(function (e) {

        tinymce.init({
            selector: '.simple-editor',
            height: 120,
            menubar: false,
            theme: 'modern',
            plugins: 'wordcount code help',
            toolbar: 'undo redo | copy paste  | help',
            content_css: [
                '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                '//www.sun3dcorporation.com/css/3dsun.css']
            
        });  
});

