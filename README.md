# E-wallet-banking-transactions
Listen and write every banking transaction. This source based on Rezer API, created by github.com/takashato and i just simplize for PHP usage with a sample for e-wallet MOMO.

*Env:
- XAMPP, PHP.

*Database:
- MySQL.

*Explain:
- How to set up API -> "Rezer - Login and Register API Turtorial.docx".
- banktransfer.php - When we have transactions in registered banking account, it will listen and receive transaction data, then send to API addBankTransfer.
- addBankTransfer.php - API Checking data validation, then save data to database (Ex: Update balance(wallet/point) for a customer by email account).
- postData.json - Descript a sample POST data to callback API.
