import { PageProps } from '@/types';

export default function InertiaTest({ auth }: PageProps) {
  return (
    <div className="min-h-screen bg-gray-100 py-12 px-4">
      <div className="max-w-4xl mx-auto">
        <h1 className="text-4xl font-bold text-center mb-8">
          🎉 React + Inertia Test Page
        </h1>

        <div className="bg-white rounded-lg shadow-lg p-6 mb-6">
          <h2 className="text-2xl font-semibold mb-4">Setup Status</h2>
          <div className="space-y-2">
            <p className="text-green-600">✅ React 19 loaded</p>
            <p className="text-green-600">✅ Inertia.js working</p>
            <p className="text-green-600">✅ TypeScript configured</p>
            <p className="text-green-600">✅ Tailwind CSS working</p>
          </div>
        </div>

        <div className="bg-white rounded-lg shadow-lg p-6">
          <h2 className="text-2xl font-semibold mb-4">User Info</h2>
          {auth?.user ? (
            <div className="space-y-2">
              <p><strong>Name:</strong> {auth.user.name}</p>
              <p><strong>Email:</strong> {auth.user.email}</p>
              <p><strong>Role:</strong> {auth.user.role}</p>
            </div>
          ) : (
            <p className="text-gray-600">Not authenticated</p>
          )}
        </div>
      </div>
    </div>
  );
}
