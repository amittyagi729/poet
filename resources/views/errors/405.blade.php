<!DOCTYPE html>
<html lang="en">
<head>
        <link href="{{asset('/assets/material/css/material-kit.css')}}" rel="stylesheet" />
        <link href="{{asset('/assets/css/main.css')}}" rel="stylesheet" />
</head>
<body class="errorscls">
    <div class="container">
      <div class="row text-center">
        <div class="col-lg-6 offset-lg-3 col-sm-6 offset-sm-3 col-12 p-3 error-main">
          <div class="row">
            <div class="col-lg-8 col-12 col-sm-10 offset-lg-2 offset-sm-1">
              <h1 class="m-0">405</h1>
              <h6>Sorry, this page doesn't exist.</h6>
            
                <a href="{{url('/')}}"><button type="submit" class="btn btn-success btn-round" style="color:#ffff; background: #e3ba26;"> Go back to ALM <div class="ripple-container"></div></button>
                </a>
            </div>
          </div>
        </div>
      </div>
    </div>
</body>
</html>