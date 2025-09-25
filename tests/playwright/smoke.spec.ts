import { test, expect } from '@playwright/test';

test.describe('Smoke Tests', () => {
  test('homepage loads @smoke', async ({ page }) => {
    const response = await page.goto('https://strybk.illbedev.com');
    
    // Check response status
    expect(response?.status()).toBeLessThan(400);
    
    // Check page has content
    await expect(page).toHaveTitle(/Strybk/i);
  });

  test('login page loads @smoke', async ({ page }) => {
    await page.goto('https://strybk.illbedev.com/login');
    
    // Check login form elements
    await expect(page.getByLabel('Email')).toBeVisible();
    await expect(page.getByLabel('Password')).toBeVisible();
    await expect(page.getByRole('button', { name: 'Sign in' })).toBeVisible();
  });

  test('navigation links work @smoke', async ({ page }) => {
    // Login first
    await page.goto('https://strybk.illbedev.com/login');
    await page.getByLabel('Email').fill('tonyjr.design@gmail.com');
    await page.getByLabel('Password').fill('Ts5h7a2w6!');
    await page.getByRole('button', { name: 'Sign in' }).click();
    
    // Wait for dashboard
    await expect(page).toHaveURL(/\/dashboard/);
    
    // Test navigation to books
    await page.getByRole('link', { name: /Books/i }).click();
    await expect(page).toHaveURL(/\/books/);
    
    // Test navigation back to dashboard
    await page.getByRole('link', { name: /Dashboard/i }).click();
    await expect(page).toHaveURL(/\/dashboard/);
  });

  test('responsive design works @smoke', async ({ page }) => {
    await page.goto('https://strybk.illbedev.com/login');
    
    // Test desktop viewport
    await page.setViewportSize({ width: 1920, height: 1080 });
    await expect(page.getByRole('button', { name: 'Sign in' })).toBeVisible();
    
    // Test tablet viewport
    await page.setViewportSize({ width: 768, height: 1024 });
    await expect(page.getByRole('button', { name: 'Sign in' })).toBeVisible();
    
    // Test mobile viewport
    await page.setViewportSize({ width: 375, height: 667 });
    await expect(page.getByRole('button', { name: 'Sign in' })).toBeVisible();
  });
});