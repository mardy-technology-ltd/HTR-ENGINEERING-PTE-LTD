# Coming Soon Page - Setup Instructions

## Overview
A branded "Coming Soon" page with 10-day countdown timer has been implemented with secret bypass access for UAT (User Acceptance Testing).

## Features
✅ Beautiful branded design matching HTR ENGINEERING colors (Blue theme)
✅ 10-day countdown timer (Days, Hours, Minutes, Seconds)
✅ Real-time JavaScript countdown
✅ Contact information displayed
✅ Secret bypass route for admin access
✅ Session-based access control

## How to Enable/Disable

### To ENABLE Coming Soon Page:

1. **Add to your `.env` file:**
   ```
   COMING_SOON_ENABLED=true
   ```

2. **Clear cache:**
   Visit: `http://rollershuttersingapore.com/clear.php?token=HTR2025!Clear`

3. **Test:** Visit your site - you'll see the coming soon page

### To DISABLE Coming Soon Page:

1. **Update `.env` file:**
   ```
   COMING_SOON_ENABLED=false
   ```
   
2. **Or remove the line completely**

3. **Clear cache again**

## Secret Bypass Access (For UAT)

### To Access Full Site:

Visit this secret URL:
```
http://rollershuttersingapore.com/secret-access
```

**What happens:**
- A session is created with bypass access
- You'll be redirected to homepage
- You can now browse the entire site normally
- Other visitors still see "Coming Soon" page

### To Remove Access:

Clear your browser cookies/session, or visit in incognito mode to see the coming soon page again.

## Testing Locally

1. **Enable coming soon:**
   Add `COMING_SOON_ENABLED=true` to `.env`

2. **Start server:**
   ```bash
   php artisan serve
   ```

3. **Visit:** `http://localhost:8000`
   - You'll see coming soon page

4. **Get bypass access:** `http://localhost:8000/secret-access`
   - Now you can access full site

## Countdown Timer

- **Launch Date:** Automatically set to 10 days from when page is accessed
- **Updates:** Real-time every second
- **Format:** DD:HH:MM:SS with leading zeros

## Design Elements

### Brand Colors Used:
- **Primary:** Blue (#1e40af - blue-900)
- **Secondary:** Light Blue (#3b82f6 - blue-500)
- **Background:** Gradient from blue-900 to blue-800
- **Text:** White with blue tints

### Components:
- Company logo with glow effect
- Animated countdown boxes
- Contact cards (Phone & Email)
- Background pattern (same as main site)
- Responsive design (mobile-friendly)

## Files Modified/Created:

1. **Middleware:** `app/Http/Middleware/ComingSoonMiddleware.php`
2. **View:** `resources/views/coming-soon.blade.php`
3. **Routes:** `routes/web.php` (secret-access route)
4. **Config:** `bootstrap/app.php` (middleware registration)

## Security Notes:

⚠️ **Important:**
- Keep the secret URL `/secret-access` confidential
- Only share with team members who need UAT access
- Consider changing the route name if needed
- The coming soon page is only active when `COMING_SOON_ENABLED=true`

## Troubleshooting:

**Issue:** Coming soon not showing
- Check `.env` has `COMING_SOON_ENABLED=true`
- Clear cache using the clear.php script
- Check if you have bypass session active

**Issue:** Can't access after visiting secret route
- Clear browser cookies
- Use incognito/private browsing
- Restart browser

**Issue:** Countdown not working
- Check JavaScript console for errors
- Ensure Vite assets are compiled: `npm run build`

## Production Deployment:

After deployment:
1. Add `COMING_SOON_ENABLED=true` to production `.env`
2. Clear cache: Visit clear.php URL
3. Test the coming soon page
4. Share secret access URL with team
5. Perform UAT while visitors see coming soon

## To Remove Completely (After Launch):

1. Set `COMING_SOON_ENABLED=false` in `.env`
2. Or delete the environment variable
3. Clear cache
4. Optionally remove middleware from `bootstrap/app.php` if not needed anymore

---

**Created:** December 6, 2025
**Author:** GitHub Copilot AI Assistant
**Project:** HTR ENGINEERING PTE LTD
