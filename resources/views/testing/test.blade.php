<html>
  <head>
    <title>reCAPTCHA demo: Simple page</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  </head>
  <body>
    <form action="{{route('testform')}}" method="POST">
      <div class="g-recaptcha" data-sitekey="6LdDoRUnAAAAAFpkTfmLPJjuK9Lrroh6F2Nft7B_"></div>
      <br/>
      <input type="submit" value="Submit">
    </form>
  </body>
</html>