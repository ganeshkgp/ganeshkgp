# 3D Portfolio - Ganesh K P

A cutting-edge 3D interactive portfolio website built with Vue.js 3, Three.js, and Laravel. This modern portfolio showcases 10+ years of software development experience through immersive 3D visualizations and engaging user interactions.

## 🚀 Features

### 🎨 **3D Visual Experience**
- **Interactive 3D Skills Visualization**: Floating spheres representing your tech stack with particle effects
- **3D Projects Gallery**: First-person navigation through an immersive 3D environment
- **Dynamic Lighting & Effects**: Neon glow effects, animations, and smooth transitions
- **Mobile-First Design**: Optimized for all devices with touch controls

### 🛠️ **Tech Stack**
- **Frontend**: Vue.js 3 + Three.js + Pinia (State Management)
- **Backend**: Laravel 12 + PHP 8.2
- **Database**: MySQL/SQLite
- **Build Tools**: Vite + Tailwind CSS v4
- **PWA**: Service Worker + Manifest for offline capability

### 🎮 **Interactive Elements**
- **Skill Spheres**: Click and hover interactions with audio feedback
- **Project Gallery**: Navigate with W/A/S/D controls, explore projects in 3D space
- **Audio System**: Background music, sound effects, and volume controls
- **Contact Form**: Working contact form with email notifications
- **Admin Panel**: Complete CRUD system for content management

### 📱 **Mobile Optimization**
- **Responsive Design**: Adapts seamlessly to all screen sizes
- **Touch Controls**: Optimized for mobile devices
- **PWA Support**: Installable as a mobile app
- **Performance**: 60fps animations with efficient rendering

## 🏗️ **Project Structure**

```
ganeshkgp/
├── app/
│   ├── Http/Controllers/Api/    # API Controllers
│   ├── Models/                   # Eloquent Models
│   └── Mail/                    # Email Classes
├── resources/
│   ├── js/
│   │   ├── components/          # Vue Components
│   │   ├── views/              # Vue Pages
│   │   ├── router/             # Vue Router
│   │   └── utils/              # Utilities (AudioManager)
│   └── css/                   # Styles
├── database/
│   ├── migrations/            # Database Migrations
│   └── seeders/               # Database Seeders
├── public/                     # Public Assets
│   ├── manifest.json          # PWA Manifest
│   └── sw.js                 # Service Worker
└── routes/                     # API Routes
```

## 🚀 Getting Started

### Prerequisites
- PHP 8.2+
- Node.js 18+
- Composer
- MySQL or SQLite

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd ganeshkgp
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Start development servers**
   ```bash
   # Start Laravel server
   php artisan serve

   # Start Vite dev server (in another terminal)
   npm run dev
   ```

6. **Access the application**
   - Frontend: `http://localhost:8000`
   - API: `http://localhost:8000/api/v1`

## 📱 API Endpoints

### Public Endpoints
- `GET /api/v1/projects` - Get all active projects
- `GET /api/v1/projects/featured` - Get featured projects
- `GET /api/v1/skills` - Get all skills
- `POST /api/v1/contact` - Submit contact form

### Protected Endpoints (Requires Authentication)
- Projects CRUD operations
- Skills CRUD operations
- Contact message management

## 🎨 Customization

### Adding New Projects
1. Use the admin panel at `/admin`
2. Or create via API:
   ```javascript
   POST /api/v1/projects
   {
     "name": "Project Name",
     "description": "Project Description",
     "technologies": ["Tech1", "Tech2"],
     "live_url": "https://example.com",
     "github_url": "https://github.com/user/repo",
     "position": {"x": 0, "y": 0, "z": 0},
     "color": "#00ffff"
   }
   ```

### Customizing 3D Skills
Edit the `skills` array in `SkillsVisualization.vue`:
```javascript
const skills = [
  {
    name: 'Your Skill',
    experience_level: 'Your Level',
    icon: '🎯',
    color: '#00ffff',
    position: { x: 0, y: 0, z: 0 },
    radius: 0.8,
    proficiency: 0.95
  }
]
```

### Audio Customization
The AudioManager uses Web Audio API to generate sounds programmatically. You can modify the sound generation functions in `resources/js/utils/AudioManager.js` to create different audio effects.

## 🔧 Configuration

### Environment Variables
```env
APP_NAME="3D Portfolio"
APP_ENV=local
APP_KEY=your-app-key
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ganeshkgp
DB_USERNAME=root
DB_PASSWORD=password

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
```

## 📱 PWA Features

### Installation
- Installable as a mobile app
- Works offline with cached content
- Fast loading with service worker

### Mobile Features
- Touch-optimized controls
- Responsive 3D scenes
- Swipe gestures for navigation
- Adaptive performance

## 🎮 3D Controls

### Skills Visualization
- **Mouse**: Hover over skills to highlight
- **Click**: Select a skill for details
- **Scroll**: Rotate the entire scene
- **Touch**: Tap and drag to rotate

### Projects Gallery
- **W/A/S/D**: Move around in 3D space
- **Mouse**: Look around (click to lock)
- **Click**: Select projects to view details
- **Touch**: On-screen controls for mobile

## 🔊 Audio Features

### Sound Effects
- **Hover**: Gentle chime when hovering over objects
- **Click**: Confirmation sound
- **Success**: Melodic tone for successful actions
- **Navigation**: Subtle audio feedback

### Background Music
- Ambient electronic music loop
- Volume control
- Mute/unmute functionality
- Auto-pauses when tab is hidden

## 🚀 Deployment

### Production Build
```bash
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Server Requirements
- PHP 8.2+ with extensions: bcmath, ctype, fileinfo, json, mbstring, openssl, pdo, tokenizer, xml
- MySQL 5.7+ or SQLite 3.8+
- Node.js 18+ for build tools
- Web server (Apache/Nginx) with mod_rewrite

### Optimization
- Image compression and lazy loading
- Code splitting for better performance
- Service worker for offline functionality
- CDN integration for static assets

## 📊 Performance

### Web Vitals Target
- **LCP**: < 2.5s
- **FID**: < 100ms
- **CLS**: < 0.1

### Optimizations
- GPU acceleration for 3D rendering
- Efficient Three.js scene management
- Lazy loading for 3D models
- Optimized audio processing

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 Author

**Ganesh K P** - Creative Developer with 10+ years of experience

- 🌐 [Portfolio](http://localhost:8000)
- 💼 [LinkedIn](https://linkedin.com/in/ganeshkp)
- 🐙 [GitHub](https://github.com/ganeshkgp)
- 📧 [Email](mailto:ganeshr848@gmail.com)

---

## 🎉 Showcase Your Skills!

This 3D portfolio demonstrates advanced web development capabilities including:
- **Modern Frontend**: Vue.js 3 with Composition API
- **3D Graphics**: Three.js with WebGL rendering
- **Backend Development**: Laravel API with full CRUD operations
- **Progressive Web App**: Offline capability and mobile installation
- **Audio Engineering**: Web Audio API integration
- **Performance Optimization**: Efficient rendering and caching
- **Mobile Optimization**: Touch controls and responsive design

Perfect for attracting clients and showcasing your full-stack development expertise! 🚀