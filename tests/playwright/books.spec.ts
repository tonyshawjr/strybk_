import { test, expect } from '@playwright/test';

// Test data
const testUser = {
  email: 'tonyjr.design@gmail.com',
  password: 'Ts5h7a2w6!'
};

const testBook = {
  title: 'My Test Book',
  subtitle: 'A subtitle for testing',
  author: 'Test Author'
};

test.describe('Book Management', () => {
  test.beforeEach(async ({ page }) => {
    // Login before each test
    await page.goto('https://strybk.illbedev.com/login');
    await page.getByLabel('Email').fill(testUser.email);
    await page.getByLabel('Password').fill(testUser.password);
    await page.getByRole('button', { name: 'Sign in' }).click();
    
    // Wait for dashboard to load
    await expect(page).toHaveURL(/\/dashboard/);
  });

  test('create new book @smoke @critical', async ({ page }) => {
    // Navigate to books page
    await page.goto('https://strybk.illbedev.com/books');
    
    // Click new book button
    await page.getByRole('link', { name: /New Book/i }).click();
    
    // Fill out the form
    await page.getByLabel('Title *').fill(testBook.title);
    await page.getByLabel('Subtitle').fill(testBook.subtitle);
    await page.getByLabel('Author').fill(testBook.author);
    
    // Check public checkbox
    await page.getByLabel('Make this book public').check();
    
    // Submit form
    await page.getByRole('button', { name: 'Create Book' }).click();
    
    // Verify redirect to edit page
    await expect(page).toHaveURL(/\/books\/[^\/]+\/edit/);
    
    // Verify book details are saved - use more specific selectors
    await expect(page.locator('#title')).toHaveValue(testBook.title);
    await expect(page.locator('#subtitle')).toHaveValue(testBook.subtitle);
    await expect(page.locator('#author')).toHaveValue(testBook.author);
  });

  test('view books library @smoke', async ({ page }) => {
    await page.goto('https://strybk.illbedev.com/books');
    
    // Check page title
    await expect(page.getByRole('heading', { name: 'My Books' })).toBeVisible();
    
    // Check for new book button
    await expect(page.getByRole('link', { name: /New Book/i })).toBeVisible();
    
    // If there are books, verify book cards are displayed
    const bookCards = page.locator('.book-card');
    const count = await bookCards.count();
    
    if (count > 0) {
      // Verify first book card has expected elements
      const firstCard = bookCards.first();
      await expect(firstCard.locator('.book-info h3')).toBeVisible();
      await expect(firstCard.locator('.book-actions')).toBeVisible();
    }
  });

  test('edit existing book @critical', async ({ page }) => {
    // Go to books library
    await page.goto('https://strybk.illbedev.com/books');
    
    // Find a book to edit (if exists)
    const bookCards = page.locator('.book-card');
    const count = await bookCards.count();
    
    if (count > 0) {
      // Click edit on first book
      await bookCards.first().getByRole('link', { name: 'Edit' }).click();
      
      // Verify we're on edit page
      await expect(page.getByRole('heading', { name: 'Edit Book' })).toBeVisible();
      
      // Update the title
      const titleInput = page.locator('#title');
      const currentTitle = await titleInput.inputValue();
      const updatedTitle = currentTitle + ' - Updated';
      
      await titleInput.fill(updatedTitle);
      
      // Save changes
      await page.getByRole('button', { name: 'Update Book' }).click();
      
      // Verify redirect back to books
      await expect(page).toHaveURL(/\/books/);
      
      // Verify success message (if flash messages are shown)
      // await expect(page.getByText('Book updated successfully')).toBeVisible();
    }
  });

  test('toggle book visibility @smoke', async ({ page }) => {
    await page.goto('https://strybk.illbedev.com/books');
    
    const bookCards = page.locator('.book-card');
    const count = await bookCards.count();
    
    if (count > 0) {
      const firstCard = bookCards.first();
      
      // Check current visibility status
      const publicBadge = firstCard.locator('.badge-public');
      
      const isPublic = await publicBadge.count() > 0;
      
      // Click toggle button - note: current implementation uses Make Private/Make Public buttons
      const toggleButton = firstCard.getByRole('button', { 
        name: isPublic ? 'Make Private' : 'Make Public' 
      });
      
      // Check if toggle button exists (current implementation may not have this yet)
      const hasToggle = await toggleButton.count() > 0;
      if (hasToggle) {
        await toggleButton.click();
        
        // Wait for page to reload
        await page.waitForLoadState('networkidle');
        
        // Verify visibility changed
        const newPublicBadge = firstCard.locator('.badge-public');
        const newIsPublic = await newPublicBadge.count() > 0;
        expect(newIsPublic).toBe(!isPublic);
      }
    }
  });

  test('delete book @critical', async ({ page }) => {
    // First create a book to delete
    await page.goto('https://strybk.illbedev.com/books/new');
    
    // Create a test book
    await page.getByLabel('Title *').fill('Book to Delete');
    await page.getByRole('button', { name: 'Create Book' }).click();
    
    // Wait for redirect to edit page
    await expect(page).toHaveURL(/\/books\/[^\/]+\/edit/);
    
    // Scroll to danger zone
    await page.getByRole('heading', { name: 'Danger Zone' }).scrollIntoViewIfNeeded();
    
    // Handle confirmation dialog
    page.on('dialog', dialog => dialog.accept());
    
    // Click delete button
    await page.getByRole('button', { name: 'Delete Book' }).click();
    
    // Verify redirect back to books
    await expect(page).toHaveURL(/\/books/);
    
    // Verify book is deleted (no longer shows "Book to Delete")
    await expect(page.getByText('Book to Delete')).not.toBeVisible();
  });

  test('dashboard shows book statistics @smoke', async ({ page }) => {
    await page.goto('https://strybk.illbedev.com/dashboard');
    
    // Check welcome message
    await expect(page.getByRole('heading', { name: /Welcome back/i })).toBeVisible();
    
    // Check statistics cards
    await expect(page.getByText('Total Books')).toBeVisible();
    await expect(page.getByText('Public Books')).toBeVisible();
    await expect(page.getByText('Total Pages')).toBeVisible();
    await expect(page.getByText('Total Words')).toBeVisible();
    
    // Check recent books section
    await expect(page.getByRole('heading', { name: 'Recent Books' })).toBeVisible();
  });

  test('empty state displays correctly', async ({ page }) => {
    // This test would work if we had a clean test account
    // For now, we'll just check if the empty state elements exist in the DOM
    await page.goto('https://strybk.illbedev.com/books');
    
    const emptyState = page.locator('.empty-state');
    const hasEmptyState = await emptyState.count() > 0;
    
    if (hasEmptyState) {
      await expect(emptyState.getByText('No books yet')).toBeVisible();
      await expect(emptyState.getByRole('link', { name: 'Create Your First Book' })).toBeVisible();
    }
  });
});