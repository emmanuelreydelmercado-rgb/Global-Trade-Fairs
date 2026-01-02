# Migrate Database from XAMPP to Aiven MySQL

## Overview
This guide helps you migrate your local XAMPP MySQL database to Aiven cloud MySQL for Render deployment.

## Prerequisites
✅ Aiven MySQL database created (DONE!)
✅ XAMPP MySQL running locally
✅ MySQL Workbench installed (DONE!)

---

## Step-by-Step Migration

### **Step 1: Export Local Database**

#### Option A: Using the batch script (Recommended)
1. Make sure XAMPP MySQL is running
2. Double-click `export-database.bat`
3. This creates `sqlamas_backup.sql` file

#### Option B: Using MySQL Workbench
1. Open MySQL Workbench
2. Connect to your local `sqlamas` database
3. Go to **Server** → **Data Export**
4. Select `sqlamas` database
5. Choose **Export to Self-Contained File**
6. Save as `sqlamas_backup.sql`
7. Click **Start Export**

---

### **Step 2: Import to Aiven MySQL**

#### Option A: Using the batch script (Recommended)
1. Make sure `sqlamas_backup.sql` exists
2. Double-click `import-to-aiven.bat`
3. Enter your Aiven password when prompted
4. Wait for import to complete

#### Option B: Using MySQL Workbench
1. Open MySQL Workbench
2. Create a new connection with these details:
   - **Connection Name**: Aiven MySQL
   - **Hostname**: `mysql-383cd7ab-emmanuelreydelmercado.h.aivencloud.com`
   - **Port**: `13763`
   - **Username**: `avnadmin`
   - **Password**: (your Aiven password)
3. Connect to Aiven
4. Go to **Server** → **Data Import**
5. Select **Import from Self-Contained File**
6. Choose `sqlamas_backup.sql`
7. Select **defaultdb** as target schema
8. Click **Start Import**

---

### **Step 3: Configure Laravel for Aiven**

Your `.env.aiven` file has been created with the configuration template.

**For Render Deployment:**
1. Go to your Render dashboard
2. Select your web service
3. Go to **Environment** tab
4. Add these environment variables:

```
DB_CONNECTION=mysql
DB_HOST=mysql-383cd7ab-emmanuelreydelmercado.h.aivencloud.com
DB_PORT=13763
DB_DATABASE=defaultdb
DB_USERNAME=avnadmin
DB_PASSWORD=your_aiven_password
```

---

### **Step 4: Test the Connection**

#### Local Test (Optional)
1. Temporarily update your local `.env` file with Aiven credentials
2. Run: `php artisan config:clear`
3. Run: `php artisan migrate:status`
4. You should see your tables listed

#### On Render
1. After setting environment variables
2. Deploy your app
3. Check logs for database connection success

---

## Verification Checklist

- [ ] Local database exported successfully
- [ ] Data imported to Aiven MySQL
- [ ] Render environment variables configured
- [ ] Application deployed and running
- [ ] Database queries working on Render
- [ ] All tables and data visible

---

## Troubleshooting

### Export Failed
- Check XAMPP MySQL is running
- Verify database name is `sqlamas`
- Check MYSQL_PATH in export-database.bat

### Import Failed
- Verify Aiven password is correct
- Check internet connection
- Ensure sqlamas_backup.sql file exists
- Try using MySQL Workbench instead

### Connection Errors on Render
- Double-check all environment variables
- Ensure no extra spaces in values
- Verify Aiven credentials are correct
- Check Render logs for specific error messages

---

## Important Notes

⚠️ **SSL Connection**: Aiven may require SSL. If you get SSL errors, you'll need to:
1. Download the CA certificate from Aiven
2. Add it to your Laravel config
3. Update `database.php` MySQL configuration

🔒 **Security**: Never commit `.env.aiven` to Git
💡 **Keep Local**: Keep your local XAMPP setup for development

---

## Next Steps

After successful migration:
1. Test all CRUD operations on Render
2. Verify user authentication works
3. Check admin dashboard functionality
4. Test form submissions

Good luck! 🚀
