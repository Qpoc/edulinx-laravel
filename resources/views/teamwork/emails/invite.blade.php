<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course Invitation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Course Invitation</div>
                    <div class="card-body">
                        <h4 class="card-title">You're Invited to Join Our Course!</h4>
                        <p class="card-text">Dear [Recipient's Name],</p>
                        <p class="card-text">We're excited to invite you to join our virtual classroom! [Your Organization/School] is using [Platform Name] to create an engaging learning experience.</p>
                        <p class="card-text">Course Name: {{ $team->name }}</p>
                        <p class="card-text">Instructor: [Instructor's Name]</p>
                        <p class="card-text">Join our classroom to access course materials, participate in discussions, and collaborate with fellow students. Get ready for an enriching learning journey!</p>
                        <a href="{{env('CLIENT_URL') . '/course-invitation?token=' . $invite->accept_token}}" class="btn btn-primary">Join Classroom</a>
                        <p class="card-text">We look forward to having you onboard!</p>
                        <p class="card-text">Best Regards, [Your Organization/School Name]</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
