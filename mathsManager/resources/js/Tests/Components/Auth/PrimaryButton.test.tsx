import { describe, it, expect } from 'vitest';
import { render, screen } from '@testing-library/react';
import PrimaryButton from '@/Components/Common/Form/PrimaryButton';

describe('PrimaryButton', () => {
  it('renders children correctly', () => {
    render(<PrimaryButton>Click me</PrimaryButton>);
    expect(screen.getByText('Click me')).toBeInTheDocument();
  });

  it('applies disabled state correctly', () => {
    render(<PrimaryButton disabled>Submit</PrimaryButton>);
    const button = screen.getByRole('button');
    expect(button).toBeDisabled();
    expect(button).toHaveClass('opacity-25');
  });

  it('handles onClick event', () => {
    let clicked = false;
    render(<PrimaryButton onClick={() => (clicked = true)}>Click</PrimaryButton>);
    screen.getByRole('button').click();
    expect(clicked).toBe(true);
  });

  it('applies custom className', () => {
    render(<PrimaryButton className="custom-class">Button</PrimaryButton>);
    expect(screen.getByRole('button')).toHaveClass('custom-class');
  });
});
