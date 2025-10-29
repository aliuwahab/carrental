<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Rental Ghana</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">Welcome to Rental Ghana!</h1>
    </div>
    
    <div style="background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px;">
        <h2 style="color: #667eea;">Hello {{ $user->name }},</h2>
        
        <p>Thank you for booking with Rental Ghana! We've created an account for you to manage your bookings and make future reservations even easier.</p>
        
        <div style="background: white; padding: 20px; border-left: 4px solid #667eea; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #667eea;">Your Account Details</h3>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Temporary Password:</strong> <code style="background: #f0f0f0; padding: 5px 10px; border-radius: 4px;">{{ $temporaryPassword }}</code></p>
        </div>
        
        <p><strong>Important:</strong> For your security, we recommend that you change this temporary password as soon as possible.</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $resetUrl }}" style="display: inline-block; background: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;">Set Your Password</a>
        </div>
        
        <p>You can also log in with your temporary password and change it from your account settings:</p>
        <p><a href="{{ route('login') }}" style="color: #667eea;">{{ route('login') }}</a></p>
        
        <hr style="border: none; border-top: 1px solid #ddd; margin: 30px 0;">
        
        <h3 style="color: #667eea;">What's Next?</h3>
        <ul style="padding-left: 20px;">
            <li>Complete your booking payment</li>
            <li>View and manage your bookings from your dashboard</li>
            <li>Browse our fleet of vehicles</li>
            <li>Check out our property rentals</li>
        </ul>
        
        <p>If you have any questions or need assistance, please don't hesitate to contact us.</p>
        
        <p style="margin-top: 30px;">
            Best regards,<br>
            <strong>The Rental Ghana Team</strong>
        </p>
    </div>
    
    <div style="text-align: center; padding: 20px; color: #999; font-size: 12px;">
        <p>&copy; {{ date('Y') }} Rental Ghana. All rights reserved.</p>
    </div>
</body>
</html>
