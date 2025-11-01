<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'title' => 'Laravel Development',
                'description' => 'Full-stack web application development using Laravel framework with modern PHP practices, RESTful APIs, and enterprise-level architecture.',
                'icon' => '🔧',
                'color' => '#ff2d20',
                'features' => [
                    'RESTful API Development',
                    'Eloquent ORM & Database Design',
                    'Authentication & Authorization',
                    'Queue System & Background Jobs',
                    'File Storage & Media Management',
                    'Laravel Package Development'
                ],
                'sort_order' => 1,
            ],
            [
                'title' => 'Vue.js Development',
                'description' => 'Modern frontend development with Vue.js 3, Composition API, Pinia state management, and ecosystem integration for reactive user interfaces.',
                'icon' => '⚛️',
                'color' => '#42b883',
                'features' => [
                    'Vue 3 Composition API',
                    'Pinia State Management',
                    'Vue Router for SPA',
                    'Component Architecture',
                    'Reactive Data Binding',
                    'Vue Ecosystem Integration'
                ],
                'sort_order' => 2,
            ],
            [
                'title' => 'MERN Stack Development',
                'description' => 'Complete JavaScript stack development using MongoDB, Express.js, React, and Node.js for scalable full-stack applications.',
                'icon' => '🟢',
                'color' => '#68a063',
                'features' => [
                    'MongoDB Database Design',
                    'Express.js REST APIs',
                    'React Components & Hooks',
                    'Node.js Backend Services',
                    'JWT Authentication',
                    'Real-time Applications'
                ],
                'sort_order' => 3,
            ],
            [
                'title' => 'MEVN Stack Development',
                'description' => 'Modern JavaScript stack development using MongoDB, Express.js, Vue.js, and Node.js for efficient full-stack applications.',
                'icon' => '🚀',
                'color' => '#35495e',
                'features' => [
                    'MongoDB Integration',
                    'Express.js Backend',
                    'Vue.js Frontend',
                    'Node.js Runtime',
                    'Vuex State Management',
                    'Full-stack JavaScript'
                ],
                'sort_order' => 4,
            ],
            [
                'title' => 'Flutter Development',
                'description' => 'Cross-platform mobile app development using Flutter and Dart for beautiful, natively compiled applications on iOS and Android.',
                'icon' => '📱',
                'color' => '#02569b',
                'features' => [
                    'Cross-platform Mobile Apps',
                    'Dart Programming Language',
                    'Widget Composition',
                    'State Management (BLoC/Provider)',
                    'Firebase Integration',
                    'Native Performance'
                ],
                'sort_order' => 5,
            ],
            [
                'title' => 'Unity Game Development',
                'description' => '2D/3D game development using Unity engine with C# scripting, physics simulation, and multi-platform deployment capabilities.',
                'icon' => '🎮',
                'color' => '#000000',
                'features' => [
                    '2D & 3D Game Development',
                    'C# Scripting',
                    'Physics & Animation',
                    'UI/UX Design for Games',
                    'Multi-platform Deployment',
                    'Asset Integration'
                ],
                'sort_order' => 6,
            ],
            [
                'title' => 'DevOps & AWS Cloud',
                'description' => 'Cloud infrastructure setup, CI/CD pipelines, and DevOps practices using AWS services for scalable deployment solutions.',
                'icon' => '☁️',
                'color' => '#ff9900',
                'features' => [
                    'AWS Cloud Services',
                    'CI/CD Pipeline Setup',
                    'Docker Containerization',
                    'Kubernetes Orchestration',
                    'Infrastructure as Code (Terraform)',
                    'Monitoring & Logging'
                ],
                'sort_order' => 7,
            ],
            [
                'title' => 'Database Design & Optimization',
                'description' => 'Database architecture design, query optimization, and data modeling for SQL and NoSQL databases with performance tuning.',
                'icon' => '💾',
                'color' => '#4a90e2',
                'features' => [
                    'SQL Database Design',
                    'NoSQL Database Architecture',
                    'Query Optimization',
                    'Database Performance Tuning',
                    'Data Migration',
                    'Backup & Recovery Strategies'
                ],
                'sort_order' => 8,
            ],
            [
                'title' => 'API Development',
                'description' => 'RESTful API design and development with GraphQL, WebSocket support, and comprehensive documentation for seamless integration.',
                'icon' => '🔌',
                'color' => '#00d4ff',
                'features' => [
                    'RESTful API Design',
                    'GraphQL Development',
                    'WebSocket Integration',
                    'API Documentation',
                    'Authentication & Security',
                    'Rate Limiting & Caching'
                ],
                'sort_order' => 9,
            ],
            [
                'title' => 'UI/UX Design',
                'description' => 'Modern user interface and experience design with responsive layouts, accessibility, and intuitive user interactions.',
                'icon' => '🎨',
                'color' => '#ff6b6b',
                'features' => [
                    'Responsive Web Design',
                    'UI Component Libraries',
                    'User Experience Research',
                    'Prototyping & Wireframing',
                    'Accessibility Standards',
                    'Design Systems'
                ],
                'sort_order' => 10,
            ],
            [
                'title' => 'Mobile App Development',
                'description' => 'Native and cross-platform mobile application development for iOS and Android with modern frameworks and best practices.',
                'icon' => '📲',
                'color' => '#a4c639',
                'features' => [
                    'iOS (Swift/Objective-C)',
                    'Android (Kotlin/Java)',
                    'React Native',
                    'Cross-platform Solutions',
                    'App Store Deployment',
                    'Mobile UI/UX Design'
                ],
                'sort_order' => 11,
            ],
            [
                'title' => 'Machine Learning & AI',
                'description' => 'AI/ML solutions development using Python, TensorFlow, and PyTorch for intelligent applications and data analysis.',
                'icon' => '🤖',
                'color' => '#3776ab',
                'features' => [
                    'Python ML Development',
                    'TensorFlow & PyTorch',
                    'Computer Vision',
                    'Natural Language Processing',
                    'Data Analysis & Visualization',
                    'Model Deployment'
                ],
                'sort_order' => 12,
            ],
            [
                'title' => 'React Development',
                'description' => 'Modern React applications with hooks, context API, and component-based architecture for scalable frontend solutions.',
                'icon' => '⚛️',
                'color' => '#61dafb',
                'features' => [
                    'React Hooks & Components',
                    'State Management (Redux/Zustand)',
                    'Next.js Framework',
                    'React Router',
                    'TypeScript Integration',
                    'Testing (Jest/RTL)'
                ],
                'sort_order' => 13,
            ],
            [
                'title' => 'Python Development',
                'description' => 'Backend development with Django and FastAPI, data science with pandas, and automation with Python scripting.',
                'icon' => '🐍',
                'color' => '#ffd43b',
                'features' => [
                    'Django Web Framework',
                    'FastAPI Development',
                    'Data Science & Analytics',
                    'Automation Scripting',
                    'RESTful APIs',
                    'Testing & Debugging'
                ],
                'sort_order' => 14,
            ],
            [
                'title' => 'GraphQL Development',
                'description' => 'GraphQL API design and implementation with schema-first development, resolvers, and real-time subscriptions.',
                'icon' => '📊',
                'color' => '#e10098',
                'features' => [
                    'GraphQL Schema Design',
                    'Apollo Server/Client',
                    'Real-time Subscriptions',
                    'GraphQL Federation',
                    'Query Optimization',
                    'API Gateway Integration'
                ],
                'sort_order' => 15,
            ],
            [
                'title' => 'Docker & Kubernetes',
                'description' => 'Containerization and orchestration for scalable applications with microservices architecture and cloud deployment.',
                'icon' => '🐳',
                'color' => '#2496ed',
                'features' => [
                    'Docker Containerization',
                    'Kubernetes Orchestration',
                    'Microservices Architecture',
                    'Service Mesh (Istio)',
                    'CI/CD Integration',
                    'Monitoring & Logging'
                ],
                'sort_order' => 16,
            ],
            [
                'title' => 'Testing & QA',
                'description' => 'Comprehensive testing strategies including unit tests, integration tests, E2E testing, and quality assurance processes.',
                'icon' => '✅',
                'color' => '#27ae60',
                'features' => [
                    'Unit Testing (Jest/PHPUnit)',
                    'Integration Testing',
                    'E2E Testing (Cypress/Playwright)',
                    'API Testing',
                    'Performance Testing',
                    'Test-Driven Development'
                ],
                'sort_order' => 17,
            ],
            [
                'title' => 'Security & Authentication',
                'description' => 'Application security implementation including authentication, authorization, encryption, and security best practices.',
                'icon' => '🔐',
                'color' => '#e74c3c',
                'features' => [
                    'JWT & OAuth Implementation',
                    'Security Auditing',
                    'Encryption & Hashing',
                    ' penetration Testing',
                    'Security Headers',
                    'Authentication Systems'
                ],
                'sort_order' => 18,
            ],
            [
                'title' => 'Progressive Web Apps',
                'description' => 'Modern PWA development with service workers, offline functionality, and app-like experiences on the web.',
                'icon' => '📱',
                'color' => '#4285f4',
                'features' => [
                    'Service Workers',
                    'Offline Functionality',
                    'Push Notifications',
                    'App Manifests',
                    'Caching Strategies',
                    'Performance Optimization'
                ],
                'sort_order' => 19,
            ],
            [
                'title' => 'Blockchain & Web3',
                'description' => 'Blockchain development, smart contracts, and Web3 applications using Ethereum, Solidity, and modern frameworks.',
                'icon' => '⛓️',
                'color' => '#627eea',
                'features' => [
                    'Smart Contract Development',
                    'Ethereum & Solidity',
                    'Web3.js Integration',
                    'DApp Development',
                    'NFT Development',
                    'DeFi Protocols'
                ],
                'sort_order' => 20,
            ],
            [
                'title' => 'Elasticsearch & Search',
                'description' => 'Search engine implementation with Elasticsearch, full-text search, and data analytics for intelligent search solutions.',
                'icon' => '🔍',
                'color' => '#005571',
                'features' => [
                    'Elasticsearch Implementation',
                    'Full-text Search',
                    'Log Analysis (ELK Stack)',
                    'Search Optimization',
                    'Data Aggregation',
                    'Monitoring & Analytics'
                ],
                'sort_order' => 21,
            ],
            [
                'title' => 'WebRTC & Video Streaming',
                'description' => 'Real-time communication applications with WebRTC, video streaming, and live streaming solutions.',
                'icon' => '📹',
                'color' => '#ff4444',
                'features' => [
                    'WebRTC Development',
                    'Video Streaming',
                    'Real-time Communication',
                    'Media Server Setup',
                    'Live Streaming',
                    'Peer-to-Peer Connections'
                ],
                'sort_order' => 22,
            ],
            [
                'title' => 'Serverless Architecture',
                'description' => 'Serverless application development with AWS Lambda, Azure Functions, and modern cloud-native architectures.',
                'icon' => '⚡',
                'color' => '#ff9900',
                'features' => [
                    'AWS Lambda Development',
                    'Azure Functions',
                    'Serverless Framework',
                    'Event-Driven Architecture',
                    'API Gateway Integration',
                    'Cost Optimization'
                ],
                'sort_order' => 23,
            ]
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        $this->command->info('✨ Services seeded successfully!');
    }
}
