import smtplib
import sys
import shlex
from email.message import EmailMessage

# ✅ Pre-authorized sender emails
allowed_senders = [
    "felixonyango12@gmail.com",
    "sheilacherong03@gmail.com",
    "robertochieng257@gmail.com"
]

# ✅ Proper parsing of all arguments using shlex
args = shlex.split(' '.join(sys.argv[1:]))

if len(args) < 5:
    print("❌ Usage: python send_email.py sender_email app_password receiver_email subject message")
    sys.exit(1)

sender_email = args[0]
app_password = args[1]
receiver_email = args[2]
subject = args[3]
message = args[4]

# ✅ Validate sender
if sender_email not in allowed_senders:
    print(f"❌ Unauthorized sender email: {sender_email}")
    sys.exit(1)

# ✅ Compose message
msg = EmailMessage()
msg['Subject'] = subject
msg['From'] = sender_email
msg['To'] = receiver_email
msg.set_content(message)

# ✅ Send email
try:
    with smtplib.SMTP_SSL('smtp.gmail.com', 465) as smtp:
        smtp.login(sender_email, app_password)
        smtp.send_message(msg)
    print(f"✅ Email sent to {receiver_email}")
except Exception as e:
    print(f"❌ Failed to send email: {str(e)}")
    # Optional: log to file for debugging
    with open("email_error_log.txt", "a") as f:
        f.write(f"[ERROR] {str(e)}\n")
