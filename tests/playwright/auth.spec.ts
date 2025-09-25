import { test, expect } from '@playwright/test';

const testUser = {
  email: 'tonyjr.design@gmail.com',
  password: 'Ts5h7a2w6!'
};

test.describe('Authentication', () => {
  test('login with valid credentials @smoke @critical', async ({ page }) => {
    await page.goto('https://strybk.illbedev.com/login');
    
    // Check login form is visible
    await expect(page.getByRole('heading', { name: 'Login' })).toBeVisible();
    
    // Fill in credentials
    await page.getByLabel('Email').fill(testUser.email);
    await page.getByLabel('Password').fill(testUser.password);
    
    // Submit form
    await page.getByRole('button', { name: 'Sign in' }).click();
    
    // Verify successful login - should redirect to dashboard
    await expect(page).toHaveURL(/\/dashboard/);
    
    // Verify user is logged in
    await expect(page.getByText(/Welcome back/i)).toBeVisible();
  });

  test('login with invalid credentials shows error', async ({ page }) => {
    await page.goto('https://strybk.illbedev.com/login');
    
    // Fill in invalid credentials
    await page.getByLabel('Email').fill('invalid@example.com');
    await page.getByLabel('Password').fill('wrongpassword');
    
    // Submit form
    await page.getByRole('button', { name: 'Sign in' }).click();
    
    // Should stay on login page
    await expect(page).toHaveURL(/\/login/);
    
    // Should show error message
    await expect(page.getByText(/Invalid email or password/i)).toBeVisible();
  });

  test('logout redirects to login page @smoke', async ({ page }) => {
    // First login
    await page.goto('https://strybk.illbedev.com/login');
    await page.getByLabel('Email').fill(testUser.email);
    await page.getByLabel('Password').fill(testUser.password);
    await page.getByRole('button', { name: 'Sign in' }).click();
    
    // Wait for dashboard
    await expect(page).toHaveURL(/\/dashboard/);
    
    // Find and click logout button
    await page.getByRole('button', { name: /Logout/i }).click();
    
    // Should redirect to login page
    await expect(page).toHaveURL(/\/login/);
    
    // Should show logout success message
    // await expect(page.getByText(/You have been logged out/i)).toBeVisible();
  });

  test('protected pages redirect to login when not authenticated @critical', async ({ page }) => {
    // Try to access protected pages without login
    await page.goto('https://strybk.illbedev.com/dashboard');
    
    // Should redirect to login
    await expect(page).toHaveURL(/\/login/);
    
    // Try books page
    await page.goto('https://strybk.illbedev.com/books');
    
    // Should redirect to login
    await expect(page).toHaveURL(/\/login/);
  });

  test('login form validation', async ({ page }) => {
    await page.goto('https://strybk.illbedev.com/login');
    
    // Try to submit empty form
    await page.getByRole('button', { name: 'Sign in' }).click();
    
    // Check HTML5 validation (browser native)
    const emailInput = page.getByLabel('Email');
    const emailValidity = await emailInput.evaluate((el: HTMLInputElement) => el.validity.valid);
    expect(emailValidity).toBe(false);
    
    // Fill email but not password
    await emailInput.fill('test@example.com');
    await page.getByRole('button', { name: 'Sign in' }).click();
    
    // Check password validation
    const passwordInput = page.getByLabel('Password');
    const passwordValidity = await passwordInput.evaluate((el: HTMLInputElement) => el.validity.valid);
    expect(passwordValidity).toBe(false);
  });
});