# 🔐 API Authentication Examples

This document shows how to use the Sanctum-based API authentication for regular users.

## 📋 Authentication Endpoints

### Base URL
```
http://your-domain.com/api
```

### Authentication Routes
```
POST /api/auth/register      # Register new user
POST /api/auth/login         # Login existing user
GET  /api/auth/user          # Get current user info (protected)
POST /api/auth/logout        # Logout (revoke current token)
POST /api/auth/logout-all    # Logout from all devices
POST /api/auth/refresh       # Refresh token
POST /api/auth/change-password  # Change password
```

## 🚀 Usage Examples

### 1. Register New User
```bash
curl -X POST http://localhost/api/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

**Response:**
```json
{
  "success": true,
  "message": "User registered successfully",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "created_at": "2025-11-02T17:30:00.000000Z"
  },
  "token": "1|abcdef123456789...",
  "token_type": "Bearer"
}
```

### 2. Login User
```bash
curl -X POST http://localhost/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

**Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "email_verified_at": null
  },
  "token": "1|xyzabc789012345...",
  "token_type": "Bearer"
}
```

### 3. Get Current User Info
```bash
curl -X GET http://localhost/api/auth/user \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer 1|xyzabc789012345..."
```

**Response:**
```json
{
  "success": true,
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "email_verified_at": null,
    "created_at": "2025-11-02T17:30:00.000000Z"
  }
}
```

### 4. Access Protected API Endpoints
```bash
# Example: Get contact messages (protected endpoint)
curl -X GET http://localhost/api/v1/contact/messages \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer 1|xyzabc789012345..."
```

### 5. Logout
```bash
curl -X POST http://localhost/api/auth/logout \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer 1|xyzabc789012345..."
```

**Response:**
```json
{
  "success": true,
  "message": "Successfully logged out"
}
```

### 6. Logout from All Devices
```bash
curl -X POST http://localhost/api/auth/logout-all \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer 1|xyzabc789012345..."
```

### 7. Refresh Token
```bash
curl -X POST http://localhost/api/auth/refresh \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer 1|xyzabc789012345..."
```

**Response:**
```json
{
  "success": true,
  "message": "Token refreshed successfully",
  "token": "1|newtoken123456...",
  "token_type": "Bearer"
}
```

### 8. Change Password
```bash
curl -X POST http://localhost/api/auth/change-password \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer 1|xyzabc789012345..." \
  -d '{
    "current_password": "password123",
    "password": "newpassword456",
    "password_confirmation": "newpassword456"
  }'
```

## 🛡️ Error Responses

### Validation Error (422)
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password field is required."]
  }
}
```

### Authentication Error (401)
```json
{
  "message": "Unauthenticated."
}
```

### Invalid Credentials (422)
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The provided credentials are incorrect."]
  }
}
```

## 🔧 JavaScript/React Example

### Register Function
```javascript
const register = async (userData) => {
  try {
    const response = await fetch('/api/auth/register', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify(userData),
    });

    const data = await response.json();

    if (response.ok) {
      // Store token
      localStorage.setItem('auth_token', data.token);
      localStorage.setItem('user', JSON.stringify(data.user));
      return data;
    } else {
      throw new Error(data.message || 'Registration failed');
    }
  } catch (error) {
    console.error('Registration error:', error);
    throw error;
  }
};
```

### Login Function
```javascript
const login = async (credentials) => {
  try {
    const response = await fetch('/api/auth/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify(credentials),
    });

    const data = await response.json();

    if (response.ok) {
      // Store token
      localStorage.setItem('auth_token', data.token);
      localStorage.setItem('user', JSON.stringify(data.user));
      return data;
    } else {
      throw new Error(data.message || 'Login failed');
    }
  } catch (error) {
    console.error('Login error:', error);
    throw error;
  }
};
```

### API Request with Authentication
```javascript
const apiRequest = async (url, options = {}) => {
  const token = localStorage.getItem('auth_token');

  const config = {
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      ...options.headers,
    },
    ...options,
  };

  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }

  try {
    const response = await fetch(url, config);
    const data = await response.json();

    if (response.ok) {
      return data;
    } else {
      throw new Error(data.message || 'Request failed');
    }
  } catch (error) {
    console.error('API request error:', error);
    throw error;
  }
};

// Usage example
const getUser = () => apiRequest('/api/auth/user');
const getProjects = () => apiRequest('/api/v1/projects');
```

## 🔑 Security Notes

1. **Token Storage:** Store tokens securely (localStorage for web, AsyncStorage for React Native)
2. **HTTPS:** Always use HTTPS in production
3. **Token Expiration:** Tokens don't expire by default, consider implementing token rotation
4. **Password Security:** Enforce strong passwords on the frontend
5. **Logout:** Always call logout endpoint to revoke tokens
6. **Error Handling:** Handle 401 responses by redirecting to login

## 📱 Vue.js Integration Example

### API Service
```javascript
// services/api.js
import axios from 'axios';

const api = axios.create({
  baseURL: '/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Add auth token to requests
api.interceptors.request.use(config => {
  const token = localStorage.getItem('auth_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Handle auth errors
api.interceptors.response.use(
  response => response,
  error => {
    if (error.response?.status === 401) {
      // Clear tokens and redirect to login
      localStorage.removeItem('auth_token');
      localStorage.removeItem('user');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

export default api;
```

### Auth Store (Pinia)
```javascript
// stores/auth.js
import { defineStore } from 'pinia';
import api from '@/services/api';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('auth_token'),
    isAuthenticated: false,
  }),

  actions: {
    async login(credentials) {
      try {
        const response = await api.post('/auth/login', credentials);
        this.setAuth(response.data);
        return response.data;
      } catch (error) {
        throw error;
      }
    },

    async register(userData) {
      try {
        const response = await api.post('/auth/register', userData);
        this.setAuth(response.data);
        return response.data;
      } catch (error) {
        throw error;
      }
    },

    async logout() {
      try {
        await api.post('/auth/logout');
      } catch (error) {
        console.error('Logout error:', error);
      } finally {
        this.clearAuth();
      }
    },

    setAuth(data) {
      this.user = data.user;
      this.token = data.token;
      this.isAuthenticated = true;
      localStorage.setItem('auth_token', data.token);
      localStorage.setItem('user', JSON.stringify(data.user));
    },

    clearAuth() {
      this.user = null;
      this.token = null;
      this.isAuthenticated = false;
      localStorage.removeItem('auth_token');
      localStorage.removeItem('user');
    },
  },
});
```

---

**🚀 Your API authentication is now ready to use!**