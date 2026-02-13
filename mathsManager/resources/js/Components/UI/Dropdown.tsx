import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/react';
import { ReactNode } from 'react';

interface DropdownItem {
  label: string;
  href?: string;
  onClick?: () => void;
  method?: 'get' | 'post';
}

interface DropdownProps {
  trigger: ReactNode;
  items: DropdownItem[];
  header?: {
    title: string;
    subtitle?: string;
  };
}

/**
 * Dropdown menu component with optional header and customizable items
 *
 * @component
 * @example
 * ```tsx
 * <Dropdown
 *   trigger={<button>Menu</button>}
 *   header={{ title: "John Doe", subtitle: "john@example.com" }}
 *   items={[
 *     { label: "Profile", href: "/profile" },
 *     { label: "Logout", onClick: handleLogout }
 *   ]}
 * />
 * ```
 */
export default function Dropdown({ trigger, items, header }: DropdownProps) {
  return (
    <Menu as="div" className="relative">
      <MenuButton className="flex text-sm bg-gray-800 rounded-full lg:me-0 focus:ring-4 focus:ring-gray-300">
        {trigger}
      </MenuButton>

      <MenuItems className="absolute right-0 z-50 mt-2 w-48 origin-top-right rounded-lg bg-white divide-y divide-gray-100 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
        {header && (
          <div className="px-4 py-3">
            <span className="block text-sm text-gray-900">{header.title}</span>
            {header.subtitle && (
              <span className="block text-sm text-gray-500 truncate">{header.subtitle}</span>
            )}
          </div>
        )}

        <div className="py-2">
          {items.map((item, index) => (
            <MenuItem key={index}>
              {({ focus }) =>
                item.href ? (
                  <a
                    href={item.href}
                    className={`block px-4 py-2 text-sm text-gray-700 ${
                      focus ? 'bg-gray-100' : ''
                    }`}
                  >
                    {item.label}
                  </a>
                ) : (
                  <button
                    onClick={item.onClick}
                    className={`block w-full text-left px-4 py-2 text-sm text-gray-700 ${
                      focus ? 'bg-gray-100' : ''
                    }`}
                  >
                    {item.label}
                  </button>
                )
              }
            </MenuItem>
          ))}
        </div>
      </MenuItems>
    </Menu>
  );
}
