import { describe, it, expect } from 'vitest';
import { render, screen } from '@testing-library/react';
import userEvent from '@testing-library/user-event';
import TextInput from '@/Components/Auth/TextInput';

describe('TextInput', () => {
  it('renders with correct type', () => {
    render(<TextInput type="email" />);
    const input = screen.getByRole('textbox');
    expect(input).toHaveAttribute('type', 'email');
  });

  it('applies isFocused prop', () => {
    render(<TextInput isFocused />);
    const input = screen.getByRole('textbox');
    expect(input).toHaveFocus();
  });

  it('handles onChange event', async () => {
    const user = userEvent.setup();
    let value = '';
    render(<TextInput onChange={(e) => (value = e.target.value)} />);

    const input = screen.getByRole('textbox');
    await user.type(input, 'test@example.com');

    expect(value).toBe('test@example.com');
  });

  it('applies custom className', () => {
    render(<TextInput className="custom-input" />);
    expect(screen.getByRole('textbox')).toHaveClass('custom-input');
  });
});
