 <!-- Fonts and icons -->
 <script src="bootstrap-template/assets/js/plugin/webfont/webfont.min.js"></script>
 <script>
     WebFont.load({
         google: {
             families: ["Public Sans:300,400,500,600,700"]
         },
         custom: {
             families: [
                 "Font Awesome 5 Solid",
                 "Font Awesome 5 Regular",
                 "Font Awesome 5 Brands",
                 "simple-line-icons",
             ],
             urls: ["bootstrap-template/assets/css/fonts.min.css"],
         },
         active: function() {
             sessionStorage.fonts = true;
         },
     });
 </script>

 <!-- CSS Files -->
 <link rel="stylesheet" href="bootstrap-template/assets/css/bootstrap.min.css" />
 <link rel="stylesheet" href="bootstrap-template/assets/css/plugins.min.css" />
 <link rel="stylesheet" href="bootstrap-template/assets/css/kaiadmin.min.css" />
 <link rel="stylesheet" href="bootstrap-template/assets/css/demo.css" />
 <link rel="stylesheet" href="css/queue-stack.css" />
 <link rel="stylesheet" href="css/all-window-queue.css" />
