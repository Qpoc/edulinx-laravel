{{-- backend\resources\views\teamwork\emails\invite.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Template</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h2>You have been invited to join team {{$team->name}}</h2>
                <p>Click the link below to accept the invitation:</p>
                <a href="{{env('CLIENT_URL') . '/' . $invite->accept_token}}" class="btn btn-primary">Accept Invitation</a>
            </div>
        </div>
    </div>
</body>
</html>
