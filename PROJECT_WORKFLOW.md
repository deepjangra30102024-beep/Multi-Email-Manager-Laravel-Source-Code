# Multi Email Manager - Application Workflow

Ye document "Multi Email Manager" application ka complete technical aur user workflow define karta hai. Yeh workflow 5 main parts mein divided hai:

## 1. System Setup Workflow (For CodeCanyon Buyers)
Jab buyer Codecanyon se script purchase karke apne server par upload karega, toh workflow aisa hoga:
1. **Upload & Extract:** Buyer source code ko apne server (cPanel / VPS) par upload karega.
2. **Web Installer:** Buyer apne domain par visit karega (e.g., `domain.com/install`).
3. **Database Configuration:** Setup wizard UI ke through buyer apne database credentials enter karega.
4. **Google API Setup:** Buyer apna Google Cloud Project banayega (OAuth client ID & Secret) aur dashboard me configure karega.
5. **Admin Creation:** Buyer apna pehla admin account banayega.
6. **Completion:** Application completely setup ho jayegi aur use ke liye ready hogi.

## 2. User Authentication Workflow (End Users)
1. **Registration/Login:** User email aur password se signup ya login karega.
2. **Dashboard Access:** Login hone ke baad user ko apna dashboard dikhega jahan uske connected accounts ki summary hogi.
3. **Roles:** Admin users ko system settings aur saare users ki details dikhengi, jabki normal users ko sirf apne connected email accounts dikhenge.

## 3. Google OAuth Connection Workflow (Connect Gmail)
1. **Connect Account:** User "Connect New Gmail Account" button par click karega.
2. **Redirection to Google:** App user ko Google OAuth consent screen par bhejegi jahan user se Email (Read/Write/Send) ki permissions mangi jayengi.
3. **Callback & Token Storage:** User jab permission allow karega, Google ek `authorization_code` ke saath wapas app par redirect karega (`/oauth/google/callback`).
4. **Fetch Tokens:** App us code ko Google API ko bhej kar `access_token` aur `refresh_token` fetch karegi.
5. **Database Entry:** Tokens aur user ka Gmail address `email_accounts` table mein securely save ho jayenge. (Access tokens generally 1 hour mein expire hote hain, uske baad Refresh token ka use karke naya Access token generate kiya jayega).

## 4. Email Synchronization Workflow (Fetch Emails)
1. **User Action / Background Job:** Jab user "Inbox" open karega ya background Job run karegi, system `email_accounts` table se token nikalega.
2. **API Call:** System Google Gmail API (`users.messages.list` aur `users.messages.get`) ko call karke latest emails fetch karega.
3. **Local Caching (Optional):** Faster performance ke liye, emails ki metadata (Subject, Sender, Date, Snippet) `email_messages` table mein save ya update hogi.
4. **Display:** UI me emails list format me render honge. Pagination ke liye Google API ke `pageToken` ka use hoga.

## 5. Sending & Replying Email Workflow
1. **Compose:** User "Compose" window mein recipient email, subject, aur body (Rich text) enter karega. (Templates ka use bhi kar sakta hai).
2. **Select Account:** User dropdown se select karega ki use kis connected Gmail account se email bhejna hai.
3. **API Dispatch:** Backend us specific account ka token load karega aur Google Gmail API (`users.messages.send`) ke through MIME format mein message dispatch karega.
4. **Attachment Handling:** Agar files attached hain, toh unhe server par temporarily store karke MIME object mein attach kiya jayega, aur bhejne ke baad delete ya log kar diya jayega.
5. **Feedback:** Success hone par user ko "Email Sent Successfully" ka message milega.

## 6. Disconnect & Security Workflow
1. **Disconnect Account:** Agar user apna Gmail account disconnect karta hai, toh system `email_accounts` table se wo tokens delete kar dega aur Google ki revoke API call karke access wapas le lega.
2. **Data Deletion:** Us account se jude hue saare cached emails aur data user ki request par wipe out kar diye jayenge.

---
**Next Steps for Development:**
1. Database Schema aur Migrations likhna.
2. Models aur unke relationships (User hasMany EmailAccounts) setup karna.
3. Google API Client Package install aur configure karna.
