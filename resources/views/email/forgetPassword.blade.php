@include('email.header')

@yield('content')
<tr>
  <td>
    <span>Hello,</span>
    <p><strong>You are receiving this email because we received a password reset request for your account.</strong></p>
    <p>This password reset link will expire in 60 minutes.</p>
    <p>If you did not request a password reset, no further action is required.</p>
  </td>
</tr>
<tr>
  <td align="center" style="padding-top: 20px;">
    <a href="{{$url}}" class="btn-style">Reset Passwrod</a>                 
  </td>                        
</tr>
<tr>
  <td>
    <p>Alternatively, you can <strong>copy the URL below</strong> and paste it into your browser:</p>
  </td>
</tr>

@include('email.footer')