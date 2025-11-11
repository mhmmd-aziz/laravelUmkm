<!doctype html>
<html lang="id">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RupaNusa - Platform E-Commerce Budaya Indonesia</title>
  <script src="/_sdk/element_sdk.js"></script>
  <style>
        body {
            box-sizing: border-box;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            width: 100%;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Light Theme */
        body.light {
            background: 
                radial-gradient(circle at 20% 80%, rgba(205, 133, 63, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(184, 134, 11, 0.08) 0%, transparent 50%),
                linear-gradient(180deg, #faf8f3 0%, #f0ebe0 100%);
            color: #3d2914;
        }

        /* Dark Theme */
        body.dark {
            background: 
                radial-gradient(circle at 20% 80%, rgba(218, 165, 32, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(205, 133, 63, 0.1) 0%, transparent 50%),
                linear-gradient(180deg, #1c1611 0%, #2a1f17 100%);
            color: #e8dcc8;
        }

        /* Navigation */
        .navbar {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(20px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 25px;
            width: 90%;
            max-width: 1100px;
            border: 1px solid rgba(184, 134, 11, 0.2);
        }

        body.light .navbar {
            background: rgba(250, 248, 243, 0.85);
            box-shadow: 
                0 8px 32px rgba(184, 134, 11, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.5);
        }

        body.dark .navbar {
            background: rgba(28, 22, 17, 0.85);
            box-shadow: 
                0 8px 32px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(218, 165, 32, 0.1);
        }

        .navbar.scrolled {
            top: 10px;
            padding: 12px 25px;
            width: 85%;
        }

        .logo {
            font-size: 28px;
            font-weight: 800;
            position: relative;
            letter-spacing: 2px;
            font-family: 'Georgia', serif;
        }

        .logo::before {
            content: '';
            position: absolute;
            left: -8px;
            top: 50%;
            transform: translateY(-50%);
            width: 6px;
            height: 6px;
            background: #b8860b;
            border-radius: 50%;
            box-shadow: 
                0 0 0 3px rgba(184, 134, 11, 0.3),
                12px 0 0 -2px #daa520,
                24px 0 0 -4px #cd853f;
        }

        body.light .logo {
            color: #8b6914;
            text-shadow: 0 2px 4px rgba(184, 134, 11, 0.1);
        }

        body.dark .logo {
            color: #daa520;
            text-shadow: 0 2px 8px rgba(218, 165, 32, 0.3);
        }

        .nav-controls {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .control-btn {
            padding: 8px 16px;
            border: 1px solid;
            border-radius: 20px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 6px;
            position: relative;
            overflow: hidden;
        }

        body.light .control-btn {
            background: rgba(255, 255, 255, 0.6);
            color: #8b6914;
            border-color: rgba(184, 134, 11, 0.3);
        }

        body.dark .control-btn {
            background: rgba(44, 31, 21, 0.6);
            color: #daa520;
            border-color: rgba(218, 165, 32, 0.4);
        }

        .control-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(184, 134, 11, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .control-btn:hover::before {
            left: 100%;
        }

        .control-btn:hover {
            transform: translateY(-1px) scale(1.05);
            box-shadow: 0 6px 20px rgba(184, 134, 11, 0.2);
        }

        body.light .control-btn:hover {
            background: rgba(255, 255, 255, 0.9);
            border-color: rgba(184, 134, 11, 0.5);
        }

        body.dark .control-btn:hover {
            background: rgba(44, 31, 21, 0.9);
            border-color: rgba(218, 165, 32, 0.6);
        }

        /* Hero Section */
        .hero {
            min-height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 120px 5% 80px;
            position: relative;
            overflow: hidden;
        }

        .hero-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            opacity: 0.04;
            background-image: 
                radial-gradient(circle at 25% 25%, currentColor 2px, transparent 2px),
                radial-gradient(circle at 75% 75%, currentColor 1px, transparent 1px);
            background-size: 60px 60px, 40px 40px;
            background-position: 0 0, 30px 30px;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 20%;
            right: 10%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(184, 134, 11, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: 30%;
            left: 15%;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(205, 133, 63, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .hero-content {
            max-width: 1200px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .hero-badge {
            display: inline-block;
            padding: 10px 24px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 30px;
            animation: fadeInDown 0.8s ease;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .hero-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 2s ease;
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            50% { left: 100%; }
            100% { left: 100%; }
        }

        body.light .hero-badge {
            background: 
                linear-gradient(135deg, rgba(184, 134, 11, 0.2), rgba(218, 165, 32, 0.15)),
                linear-gradient(45deg, rgba(255, 255, 255, 0.3) 0%, transparent 100%);
            color: #6b5108;
            border: 1px solid rgba(184, 134, 11, 0.4);
            box-shadow: 
                0 4px 15px rgba(184, 134, 11, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.5);
        }

        body.dark .hero-badge {
            background: 
                linear-gradient(135deg, rgba(218, 165, 32, 0.25), rgba(205, 133, 63, 0.2)),
                linear-gradient(45deg, rgba(218, 165, 32, 0.1) 0%, transparent 100%);
            color: #f4d03f;
            border: 1px solid rgba(218, 165, 32, 0.5);
            box-shadow: 
                0 4px 15px rgba(218, 165, 32, 0.2),
                inset 0 1px 0 rgba(218, 165, 32, 0.3);
        }

        .hero h1 {
            font-size: 64px;
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 25px;
            animation: fadeInUp 0.8s ease 0.2s both;
            position: relative;
            font-family: 'Georgia', serif;
            letter-spacing: -1px;
        }

        body.light .hero h1 {
            color: #2c1810;
            text-shadow: 
                0 1px 0 rgba(184, 134, 11, 0.3),
                0 2px 0 rgba(184, 134, 11, 0.2),
                0 3px 0 rgba(184, 134, 11, 0.1),
                0 4px 8px rgba(184, 134, 11, 0.1);
        }

        body.dark .hero h1 {
            color: #e8dcc8;
            text-shadow: 
                0 1px 0 rgba(218, 165, 32, 0.4),
                0 2px 0 rgba(218, 165, 32, 0.3),
                0 3px 0 rgba(218, 165, 32, 0.2),
                0 4px 12px rgba(218, 165, 32, 0.2);
        }

        .hero h1::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 4px;
            background: linear-gradient(90deg, transparent, #b8860b, #daa520, #cd853f, transparent);
            border-radius: 2px;
        }

        .hero p {
            font-size: 20px;
            line-height: 1.6;
            margin-bottom: 40px;
            opacity: 0.9;
            animation: fadeInUp 0.8s ease 0.4s both;
        }

        .cta-button {
            display: inline-block;
            padding: 20px 50px;
            background: 
                linear-gradient(135deg, #b8860b 0%, #daa520 50%, #cd853f 100%),
                linear-gradient(45deg, rgba(255, 255, 255, 0.1) 0%, transparent 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 800;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 
                0 10px 30px rgba(184, 134, 11, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.3),
                inset 0 -1px 0 rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.8s ease 0.6s both;
            border: none;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .cta-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s ease;
        }

        .cta-button:hover::before {
            left: 100%;
        }

        .cta-button:hover {
            transform: translateY(-4px) scale(1.05);
            box-shadow: 
                0 15px 40px rgba(184, 134, 11, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.4),
                inset 0 -1px 0 rgba(0, 0, 0, 0.2);
        }

        .cta-button:active {
            transform: translateY(-2px) scale(1.02);
        }

        /* Features Section */
        .features {
            padding: 100px 5%;
            position: relative;
        }

        /* Why Choose Us Section */
        .why-choose {
            padding: 100px 5%;
            position: relative;
        }

        body.light .why-choose {
            background: 
                radial-gradient(circle at 30% 70%, rgba(184, 134, 11, 0.05) 0%, transparent 50%),
                linear-gradient(135deg, rgba(250, 248, 243, 0.8) 0%, rgba(240, 235, 224, 0.9) 100%);
        }

        body.dark .why-choose {
            background: 
                radial-gradient(circle at 30% 70%, rgba(218, 165, 32, 0.1) 0%, transparent 50%),
                linear-gradient(135deg, rgba(28, 22, 17, 0.8) 0%, rgba(42, 31, 23, 0.9) 100%);
        }

        .why-choose-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .reasons-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .reason-item {
            padding: 35px 30px;
            border-radius: 20px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        body.light .reason-item {
            background: 
                linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(250, 248, 243, 0.7) 100%);
            border: 1px solid rgba(184, 134, 11, 0.2);
            box-shadow: 0 4px 20px rgba(184, 134, 11, 0.08);
        }

        body.dark .reason-item {
            background: 
                linear-gradient(135deg, rgba(44, 31, 21, 0.9) 0%, rgba(28, 22, 17, 0.7) 100%);
            border: 1px solid rgba(218, 165, 32, 0.3);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .reason-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #b8860b, #daa520, #cd853f);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .reason-item:hover::before {
            transform: scaleX(1);
        }

        .reason-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 35px rgba(184, 134, 11, 0.15);
        }

        .reason-number {
            font-size: 48px;
            font-weight: 900;
            background: linear-gradient(135deg, #b8860b 0%, #daa520 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 20px;
            font-family: 'Georgia', serif;
            opacity: 0.8;
        }

        .reason-item h3 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 15px;
            color: #b8860b;
        }

        body.dark .reason-item h3 {
            color: #daa520;
        }

        .reason-item p {
            font-size: 15px;
            line-height: 1.7;
            opacity: 0.85;
        }

        .section-title {
            text-align: center;
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #b8860b 0%, #daa520 50%, #cd853f 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .section-subtitle {
            text-align: center;
            font-size: 18px;
            margin-bottom: 60px;
            opacity: 0.8;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 35px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .feature-card {
            padding: 45px 40px;
            border-radius: 25px;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        body.light .feature-card {
            background: 
                linear-gradient(135deg, rgba(255, 255, 255, 0.8) 0%, rgba(250, 248, 243, 0.6) 100%),
                linear-gradient(45deg, rgba(184, 134, 11, 0.05) 0%, transparent 100%);
            border: 1px solid rgba(184, 134, 11, 0.3);
            box-shadow: 
                0 8px 32px rgba(184, 134, 11, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
        }

        body.dark .feature-card {
            background: 
                linear-gradient(135deg, rgba(44, 31, 21, 0.8) 0%, rgba(28, 22, 17, 0.6) 100%),
                linear-gradient(45deg, rgba(218, 165, 32, 0.1) 0%, transparent 100%);
            border: 1px solid rgba(218, 165, 32, 0.4);
            box-shadow: 
                0 8px 32px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(218, 165, 32, 0.2);
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(from 0deg, transparent, rgba(184, 134, 11, 0.1), transparent);
            transform: rotate(0deg);
            transition: transform 0.6s ease;
            opacity: 0;
        }

        .feature-card:hover::before {
            transform: rotate(360deg);
            opacity: 1;
        }

        .feature-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #b8860b, #daa520, #cd853f);
            transform: scaleX(0);
            transition: transform 0.5s ease;
            border-radius: 25px 25px 0 0;
        }

        .feature-card:hover::after {
            transform: scaleX(1);
        }

        .feature-card:hover {
            transform: translateY(-12px) rotateX(5deg);
            box-shadow: 0 20px 50px rgba(184, 134, 11, 0.25);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            font-size: 36px;
            position: relative;
            transition: all 0.4s ease;
        }

        body.light .feature-icon {
            background: 
                linear-gradient(135deg, rgba(184, 134, 11, 0.2), rgba(218, 165, 32, 0.15)),
                linear-gradient(45deg, rgba(255, 255, 255, 0.5) 0%, transparent 100%);
            box-shadow: 
                0 8px 25px rgba(184, 134, 11, 0.15),
                inset 0 2px 0 rgba(255, 255, 255, 0.6),
                inset 0 -2px 0 rgba(184, 134, 11, 0.1);
        }

        body.dark .feature-icon {
            background: 
                linear-gradient(135deg, rgba(218, 165, 32, 0.25), rgba(205, 133, 63, 0.2)),
                linear-gradient(45deg, rgba(218, 165, 32, 0.1) 0%, transparent 100%);
            box-shadow: 
                0 8px 25px rgba(218, 165, 32, 0.2),
                inset 0 2px 0 rgba(218, 165, 32, 0.3),
                inset 0 -2px 0 rgba(0, 0, 0, 0.2);
        }

        .feature-icon::before {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            border-radius: 50%;
            background: linear-gradient(45deg, #b8860b, #daa520, #cd853f);
            z-index: -1;
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .feature-card:hover .feature-icon::before {
            opacity: 1;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotateY(15deg);
        }

        .feature-card h3 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 15px;
            color: #b8860b;
        }

        body.dark .feature-card h3 {
            color: #daa520;
        }

        .feature-card p {
            font-size: 16px;
            line-height: 1.7;
            opacity: 0.85;
        }

        /* Stats Section */
        .stats {
            padding: 80px 5%;
            text-align: center;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .stat-item {
            padding: 30px 20px;
        }

        .stat-number {
            font-size: 48px;
            font-weight: 800;
            background: linear-gradient(135deg, #b8860b 0%, #daa520 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 16px;
            opacity: 0.8;
            font-weight: 600;
        }

        /* Newsletter Section */
        .newsletter {
            padding: 80px 5%;
            position: relative;
        }

        body.light .newsletter {
            background: 
                radial-gradient(circle at 50% 50%, rgba(184, 134, 11, 0.08) 0%, transparent 70%),
                linear-gradient(135deg, #b8860b 0%, #daa520 100%);
        }

        body.dark .newsletter {
            background: 
                radial-gradient(circle at 50% 50%, rgba(218, 165, 32, 0.15) 0%, transparent 70%),
                linear-gradient(135deg, #8b6914 0%, #b8860b 100%);
        }

        .newsletter-container {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }

        .newsletter h2 {
            font-size: 36px;
            font-weight: 800;
            color: white;
            margin-bottom: 15px;
            font-family: 'Georgia', serif;
        }

        .newsletter p {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 40px;
            line-height: 1.6;
        }

        .newsletter-form {
            display: flex;
            gap: 15px;
            max-width: 500px;
            margin: 0 auto;
        }

        .newsletter-form input {
            flex: 1;
            padding: 15px 20px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            background: rgba(255, 255, 255, 0.95);
            color: #2c1810;
            outline: none;
            transition: all 0.3s ease;
        }

        .newsletter-form input:focus {
            background: white;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
        }

        .newsletter-btn {
            padding: 15px 30px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.5);
            border-radius: 50px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .newsletter-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.8);
            transform: translateY(-2px);
        }

        /* Footer */
        .footer {
            padding: 60px 5% 30px;
            position: relative;
        }

        body.light .footer {
            background: 
                linear-gradient(135deg, rgba(44, 24, 16, 0.95) 0%, rgba(28, 22, 17, 0.98) 100%);
            color: #e8dcc8;
        }

        body.dark .footer {
            background: 
                linear-gradient(135deg, rgba(16, 12, 9, 0.95) 0%, rgba(20, 15, 11, 0.98) 100%);
            color: #f5f1e8;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-content {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-section h4 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #daa520;
        }

        .footer-logo .logo {
            font-size: 32px;
            margin-bottom: 15px;
            color: #daa520;
        }

        .footer-logo p {
            font-size: 14px;
            line-height: 1.6;
            opacity: 0.8;
            margin-bottom: 0;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            color: rgba(232, 220, 200, 0.8);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            color: #daa520;
            transform: translateX(5px);
        }

        .social-links {
            display: flex;
            gap: 15px;
        }

        .social-link {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            text-decoration: none;
            transition: all 0.3s ease;
            background: rgba(218, 165, 32, 0.1);
            border: 1px solid rgba(218, 165, 32, 0.3);
        }

        .social-link:hover {
            background: rgba(218, 165, 32, 0.2);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(218, 165, 32, 0.3);
        }

        .footer-bottom {
            padding-top: 30px;
            border-top: 1px solid rgba(218, 165, 32, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-bottom p {
            font-size: 14px;
            opacity: 0.7;
            margin: 0;
        }

        .footer-badges {
            display: flex;
            gap: 15px;
        }

        .footer-badges .badge {
            padding: 6px 12px;
            background: rgba(218, 165, 32, 0.15);
            border: 1px solid rgba(218, 165, 32, 0.3);
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: #daa520;
        }

        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 42px;
            }

            .hero p {
                font-size: 18px;
            }

            .section-title {
                font-size: 36px;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .reasons-grid {
                grid-template-columns: 1fr;
            }

            .nav-controls {
                gap: 10px;
            }

            .control-btn {
                padding: 8px 14px;
                font-size: 13px;
            }

            .newsletter-form {
                flex-direction: column;
                gap: 15px;
            }

            .newsletter-btn {
                align-self: center;
                width: fit-content;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 30px;
                text-align: center;
            }

            .footer-bottom {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }

            .footer-badges {
                justify-content: center;
            }
        }
    </style>
  <style>@view-transition { navigation: auto; }</style>
  <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
  <script src="https://cdn.tailwindcss.com" type="text/javascript"></script>
 </head>
 <body class="light"><!-- Navigation -->
  <nav class="navbar" id="navbar">
   <div class="logo" id="siteLogo">
    RupaNusa
   </div>
   <div class="nav-controls"><button class="control-btn" id="langBtn" onclick="toggleLanguage()"> 🌐 <span id="langText">EN</span> </button> <button class="control-btn" id="themeBtn" onclick="toggleTheme()"> <span id="themeIcon">🌙</span> </button>
   </div>
  </nav><!-- Hero Section -->
  <section class="hero">
   <div class="hero-pattern"></div>
   <div class="hero-content">
    <div class="hero-badge" id="heroBadge">
     ✨ Platform E-Commerce Budaya Terdepan
    </div>
    <h1 id="heroTitle">Jelajahi Kekayaan Budaya Indonesia</h1>
    <p id="heroSubtitle">Platform e-commerce dengan teknologi AI untuk melestarikan dan mempromosikan produk budaya Indonesia ke seluruh dunia</p><button class="cta-button" id="ctaButton" onclick="viewProducts()">Lihat Produk Kami</button>
   </div>
  </section><!-- Features Section -->
  <section class="features">
   <h2 class="section-title" id="featuresTitle">Fitur Unggulan</h2>
   <p class="section-subtitle" id="featuresSubtitle">Teknologi AI untuk pengalaman berbelanja yang lebih cerdas</p>
   <div class="features-grid">
    <div class="feature-card">
     <div class="feature-icon">
      💬
     </div>
     <h3 id="feature1Title">Chatbot Cerdas</h3>
     <p id="feature1Desc">Asisten virtual 24/7 yang membantu Anda menemukan produk budaya yang sempurna dengan rekomendasi personal</p>
    </div>
    <div class="feature-card">
     <div class="feature-icon">
      📊
     </div>
     <h3 id="feature2Title">Insight AI</h3>
     <p id="feature2Desc">Analisis tren pasar dan preferensi pelanggan untuk membantu penjual mengoptimalkan strategi bisnis mereka</p>
    </div>
    <div class="feature-card">
     <div class="feature-icon">
      ✍️
     </div>
     <h3 id="feature3Title">AI Copywriting</h3>
     <p id="feature3Desc">Buat deskripsi produk yang menarik dan SEO-friendly secara otomatis untuk meningkatkan penjualan</p>
    </div>
   </div>
  </section><!-- Why Choose Us Section -->
  <section class="why-choose">
   <div class="why-choose-container">
    <div class="why-choose-content">
     <h2 class="section-title" id="whyChooseTitle">Mengapa Memilih RupaNusa?</h2>
     <p class="section-subtitle" id="whyChooseSubtitle">Keunggulan yang membuat kami berbeda dari platform lainnya</p>
     <div class="reasons-grid">
      <div class="reason-item">
       <div class="reason-number">
        01
       </div>
       <h3 id="reason1Title">Kualitas Terjamin</h3>
       <p id="reason1Desc">Setiap produk telah melalui kurasi ketat untuk memastikan keaslian dan kualitas terbaik dari pengrajin berpengalaman</p>
      </div>
      <div class="reason-item">
       <div class="reason-number">
        02
       </div>
       <h3 id="reason2Title">Teknologi AI Terdepan</h3>
       <p id="reason2Desc">Sistem rekomendasi cerdas dan analisis pasar yang membantu Anda menemukan produk yang tepat</p>
      </div>
      <div class="reason-item">
       <div class="reason-number">
        03
       </div>
       <h3 id="reason3Title">Dukungan Pengrajin Lokal</h3>
       <p id="reason3Desc">Setiap pembelian langsung mendukung pengrajin lokal dan pelestarian budaya Indonesia</p>
      </div>
      <div class="reason-item">
       <div class="reason-number">
        04
       </div>
       <h3 id="reason4Title">Pengiriman Aman</h3>
       <p id="reason4Desc">Sistem packaging khusus dan asuransi untuk memastikan produk budaya sampai dengan sempurna</p>
      </div>
     </div>
    </div>
   </div>
  </section><!-- Stats Section -->
  <section class="stats">
   <div class="stats-grid">
    <div class="stat-item">
     <div class="stat-number">
      10K+
     </div>
     <div class="stat-label" id="stat1Label">
      Produk Budaya
     </div>
    </div>
    <div class="stat-item">
     <div class="stat-number">
      5K+
     </div>
     <div class="stat-label" id="stat2Label">
      Pengrajin
     </div>
    </div>
    <div class="stat-item">
     <div class="stat-number">
      50K+
     </div>
     <div class="stat-label" id="stat3Label">
      Pelanggan Puas
     </div>
    </div>
    <div class="stat-item">
     <div class="stat-number">
      34
     </div>
     <div class="stat-label" id="stat4Label">
      Provinsi
     </div>
    </div>
   </div>
  </section><!-- Newsletter Section -->
  <section class="newsletter">
   <div class="newsletter-container">
    <div class="newsletter-content">
     <h2 id="newsletterTitle">Dapatkan Update Terbaru</h2>
     <p id="newsletterSubtitle">Berlangganan newsletter untuk mendapatkan info produk budaya terbaru dan penawaran eksklusif</p>
     <div class="newsletter-form"><input type="email" id="emailInput" placeholder="Masukkan email Anda"> <button class="newsletter-btn" id="subscribeBtn" onclick="subscribeNewsletter()">Berlangganan</button>
     </div>
    </div>
   </div>
  </section><!-- Footer -->
  <footer class="footer">
   <div class="footer-container">
    <div class="footer-content">
     <div class="footer-section">
      <div class="footer-logo">
       <div class="logo" id="footerLogo">
        RupaNusa
       </div>
       <p id="footerDesc">Platform e-commerce terdepan untuk produk budaya Indonesia dengan teknologi AI</p>
      </div>
     </div>
     <div class="footer-section">
      <h4 id="footerProductTitle">Produk</h4>
      <ul class="footer-links">
       <li><a href="#" id="footerLink1">Batik</a></li>
       <li><a href="#" id="footerLink2">Kerajinan Tangan</a></li>
       <li><a href="#" id="footerLink3">Perhiasan Tradisional</a></li>
       <li><a href="#" id="footerLink4">Tekstil Nusantara</a></li>
      </ul>
     </div>
     <div class="footer-section">
      <h4 id="footerCompanyTitle">Perusahaan</h4>
      <ul class="footer-links">
       <li><a href="#" id="footerLink5">Tentang Kami</a></li>
       <li><a href="#" id="footerLink6">Karir</a></li>
       <li><a href="#" id="footerLink7">Blog</a></li>
       <li><a href="#" id="footerLink8">Press Kit</a></li>
      </ul>
     </div>
     <div class="footer-section">
      <h4 id="footerSupportTitle">Dukungan</h4>
      <ul class="footer-links">
       <li><a href="#" id="footerLink9">Pusat Bantuan</a></li>
       <li><a href="#" id="footerLink10">Kontak</a></li>
       <li><a href="#" id="footerLink11">Kebijakan Privasi</a></li>
       <li><a href="#" id="footerLink12">Syarat &amp; Ketentuan</a></li>
      </ul>
     </div>
     <div class="footer-section">
      <h4 id="footerSocialTitle">Ikuti Kami</h4>
      <div class="social-links"><a href="#" class="social-link">📘</a> <a href="#" class="social-link">📷</a> <a href="#" class="social-link">🐦</a> <a href="#" class="social-link">💼</a>
      </div>
     </div>
    </div>
    <div class="footer-bottom">
     <p id="footerCopyright">© 2024 RupaNusa. Melestarikan Budaya Indonesia dengan Teknologi.</p>
     <div class="footer-badges"><span class="badge">🛡️ Aman</span> <span class="badge">✅ Terpercaya</span> <span class="badge">🇮🇩 Made in Indonesia</span>
     </div>
    </div>
   </div>
  </footer>
  <script>
        const defaultConfig = {
            site_title: "RupaNusa",
            hero_title_id: "Jelajahi Kekayaan Budaya Indonesia",
            hero_title_en: "Explore Indonesian Cultural Treasures",
            hero_subtitle_id: "Platform e-commerce dengan teknologi AI untuk melestarikan dan mempromosikan produk budaya Indonesia ke seluruh dunia",
            hero_subtitle_en: "AI-powered e-commerce platform to preserve and promote Indonesian cultural products to the world",
            feature1_title_id: "Chatbot Cerdas",
            feature1_title_en: "Smart Chatbot",
            feature2_title_id: "Insight AI",
            feature2_title_en: "AI Insights",
            feature3_title_id: "AI Copywriting",
            feature3_title_en: "AI Copywriting",
            cta_button_id: "Lihat Produk Kami",
            cta_button_en: "View Our Products",
            background_color: "#f5f1e8",
            surface_color: "#ffffff",
            text_color: "#2c1810",
            primary_action_color: "#b8860b",
            secondary_action_color: "#daa520",
            font_family: "Inter",
            font_size: 16
        };

        let currentLang = 'id';
        let currentTheme = 'light';

        const translations = {
            id: {
                badge: "✨ Platform E-Commerce Budaya Terdepan",
                featuresTitle: "Fitur Unggulan",
                featuresSubtitle: "Teknologi AI untuk pengalaman berbelanja yang lebih cerdas",
                feature1Desc: "Asisten virtual 24/7 yang membantu Anda menemukan produk budaya yang sempurna dengan rekomendasi personal",
                feature2Desc: "Analisis tren pasar dan preferensi pelanggan untuk membantu penjual mengoptimalkan strategi bisnis mereka",
                feature3Desc: "Buat deskripsi produk yang menarik dan SEO-friendly secara otomatis untuk meningkatkan penjualan",
                whyChooseTitle: "Mengapa Memilih RupaNusa?",
                whyChooseSubtitle: "Keunggulan yang membuat kami berbeda dari platform lainnya",
                reason1Title: "Kualitas Terjamin",
                reason1Desc: "Setiap produk telah melalui kurasi ketat untuk memastikan keaslian dan kualitas terbaik dari pengrajin berpengalaman",
                reason2Title: "Teknologi AI Terdepan",
                reason2Desc: "Sistem rekomendasi cerdas dan analisis pasar yang membantu Anda menemukan produk yang tepat",
                reason3Title: "Dukungan Pengrajin Lokal",
                reason3Desc: "Setiap pembelian langsung mendukung pengrajin lokal dan pelestarian budaya Indonesia",
                reason4Title: "Pengiriman Aman",
                reason4Desc: "Sistem packaging khusus dan asuransi untuk memastikan produk budaya sampai dengan sempurna",
                newsletterTitle: "Dapatkan Update Terbaru",
                newsletterSubtitle: "Berlangganan newsletter untuk mendapatkan info produk budaya terbaru dan penawaran eksklusif",
                emailPlaceholder: "Masukkan email Anda",
                subscribeBtn: "Berlangganan",
                footerDesc: "Platform e-commerce terdepan untuk produk budaya Indonesia dengan teknologi AI",
                footerProductTitle: "Produk",
                footerCompanyTitle: "Perusahaan",
                footerSupportTitle: "Dukungan",
                footerSocialTitle: "Ikuti Kami",
                stat1Label: "Produk Budaya",
                stat2Label: "Pengrajin",
                stat3Label: "Pelanggan Puas",
                stat4Label: "Provinsi",
                footerCopyright: "© 2024 RupaNusa. Melestarikan Budaya Indonesia dengan Teknologi.",
                langText: "EN"
            },
            en: {
                badge: "✨ Leading Cultural E-Commerce Platform",
                featuresTitle: "Key Features",
                featuresSubtitle: "AI technology for smarter shopping experience",
                feature1Desc: "24/7 virtual assistant helping you find the perfect cultural products with personalized recommendations",
                feature2Desc: "Market trend analysis and customer preferences to help sellers optimize their business strategies",
                feature3Desc: "Automatically create engaging and SEO-friendly product descriptions to boost sales",
                whyChooseTitle: "Why Choose RupaNusa?",
                whyChooseSubtitle: "Advantages that make us different from other platforms",
                reason1Title: "Guaranteed Quality",
                reason1Desc: "Every product has gone through strict curation to ensure authenticity and best quality from experienced artisans",
                reason2Title: "Leading AI Technology",
                reason2Desc: "Smart recommendation system and market analysis that helps you find the right products",
                reason3Title: "Local Artisan Support",
                reason3Desc: "Every purchase directly supports local artisans and preservation of Indonesian culture",
                reason4Title: "Safe Delivery",
                reason4Desc: "Special packaging system and insurance to ensure cultural products arrive perfectly",
                newsletterTitle: "Get Latest Updates",
                newsletterSubtitle: "Subscribe to newsletter for latest cultural product info and exclusive offers",
                emailPlaceholder: "Enter your email",
                subscribeBtn: "Subscribe",
                footerDesc: "Leading e-commerce platform for Indonesian cultural products with AI technology",
                footerProductTitle: "Products",
                footerCompanyTitle: "Company",
                footerSupportTitle: "Support",
                footerSocialTitle: "Follow Us",
                stat1Label: "Cultural Products",
                stat2Label: "Artisans",
                stat3Label: "Happy Customers",
                stat4Label: "Provinces",
                footerCopyright: "© 2024 RupaNusa. Preserving Indonesian Culture with Technology.",
                langText: "ID"
            }
        };

        function toggleLanguage() {
            currentLang = currentLang === 'id' ? 'en' : 'id';
            updateLanguage();
        }

        function updateLanguage() {
            const config = window.elementSdk?.config || defaultConfig;
            const t = translations[currentLang];
            
            document.getElementById('heroTitle').textContent = currentLang === 'id' ? 
                (config.hero_title_id || defaultConfig.hero_title_id) : 
                (config.hero_title_en || defaultConfig.hero_title_en);
            
            document.getElementById('heroSubtitle').textContent = currentLang === 'id' ? 
                (config.hero_subtitle_id || defaultConfig.hero_subtitle_id) : 
                (config.hero_subtitle_en || defaultConfig.hero_subtitle_en);
            
            document.getElementById('feature1Title').textContent = currentLang === 'id' ? 
                (config.feature1_title_id || defaultConfig.feature1_title_id) : 
                (config.feature1_title_en || defaultConfig.feature1_title_en);
            
            document.getElementById('feature2Title').textContent = currentLang === 'id' ? 
                (config.feature2_title_id || defaultConfig.feature2_title_id) : 
                (config.feature2_title_en || defaultConfig.feature2_title_en);
            
            document.getElementById('feature3Title').textContent = currentLang === 'id' ? 
                (config.feature3_title_id || defaultConfig.feature3_title_id) : 
                (config.feature3_title_en || defaultConfig.feature3_title_en);
            
            document.getElementById('ctaButton').textContent = currentLang === 'id' ? 
                (config.cta_button_id || defaultConfig.cta_button_id) : 
                (config.cta_button_en || defaultConfig.cta_button_en);
            
            // Update all translatable elements
            document.getElementById('heroBadge').textContent = t.badge;
            document.getElementById('featuresTitle').textContent = t.featuresTitle;
            document.getElementById('featuresSubtitle').textContent = t.featuresSubtitle;
            document.getElementById('feature1Desc').textContent = t.feature1Desc;
            document.getElementById('feature2Desc').textContent = t.feature2Desc;
            document.getElementById('feature3Desc').textContent = t.feature3Desc;
            
            // Why Choose Us section
            document.getElementById('whyChooseTitle').textContent = t.whyChooseTitle;
            document.getElementById('whyChooseSubtitle').textContent = t.whyChooseSubtitle;
            document.getElementById('reason1Title').textContent = t.reason1Title;
            document.getElementById('reason1Desc').textContent = t.reason1Desc;
            document.getElementById('reason2Title').textContent = t.reason2Title;
            document.getElementById('reason2Desc').textContent = t.reason2Desc;
            document.getElementById('reason3Title').textContent = t.reason3Title;
            document.getElementById('reason3Desc').textContent = t.reason3Desc;
            document.getElementById('reason4Title').textContent = t.reason4Title;
            document.getElementById('reason4Desc').textContent = t.reason4Desc;
            
            // Newsletter section
            document.getElementById('newsletterTitle').textContent = t.newsletterTitle;
            document.getElementById('newsletterSubtitle').textContent = t.newsletterSubtitle;
            document.getElementById('emailInput').placeholder = t.emailPlaceholder;
            document.getElementById('subscribeBtn').textContent = t.subscribeBtn;
            
            // Footer section
            document.getElementById('footerDesc').textContent = t.footerDesc;
            document.getElementById('footerProductTitle').textContent = t.footerProductTitle;
            document.getElementById('footerCompanyTitle').textContent = t.footerCompanyTitle;
            document.getElementById('footerSupportTitle').textContent = t.footerSupportTitle;
            document.getElementById('footerSocialTitle').textContent = t.footerSocialTitle;
            
            // Stats and other elements
            document.getElementById('stat1Label').textContent = t.stat1Label;
            document.getElementById('stat2Label').textContent = t.stat2Label;
            document.getElementById('stat3Label').textContent = t.stat3Label;
            document.getElementById('stat4Label').textContent = t.stat4Label;
            document.getElementById('footerCopyright').textContent = t.footerCopyright;
            document.getElementById('langText').textContent = t.langText;
        }

        function subscribeNewsletter() {
            const email = document.getElementById('emailInput').value;
            const btn = document.getElementById('subscribeBtn');
            
            if (!email || !email.includes('@')) {
                // Show error message inline
                btn.textContent = currentLang === 'id' ? 'Email tidak valid!' : 'Invalid email!';
                btn.style.background = 'rgba(255, 0, 0, 0.3)';
                setTimeout(() => {
                    btn.textContent = currentLang === 'id' ? 'Berlangganan' : 'Subscribe';
                    btn.style.background = 'rgba(255, 255, 255, 0.2)';
                }, 2000);
                return;
            }
            
            // Show success message
            btn.textContent = currentLang === 'id' ? 'Berhasil!' : 'Success!';
            btn.style.background = 'rgba(0, 255, 0, 0.3)';
            document.getElementById('emailInput').value = '';
            
            setTimeout(() => {
                btn.textContent = currentLang === 'id' ? 'Berlangganan' : 'Subscribe';
                btn.style.background = 'rgba(255, 255, 255, 0.2)';
            }, 2000);
        }

        function toggleTheme() {
            currentTheme = currentTheme === 'light' ? 'dark' : 'light';
            document.body.className = currentTheme;
            document.getElementById('themeIcon').textContent = currentTheme === 'light' ? '🌙' : '☀️';
        }

        function viewProducts() {
            window.open('https://rupanusa.com/products', '_blank', 'noopener,noreferrer');
        }

        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        async function onConfigChange(config) {
            const baseFont = config.font_family || defaultConfig.font_family;
            const baseFontStack = 'Inter, Segoe UI, Tahoma, Geneva, Verdana, sans-serif';
            const baseSize = config.font_size || defaultConfig.font_size;
            
            document.body.style.fontFamily = `${baseFont}, ${baseFontStack}`;
            document.body.style.fontSize = `${baseSize}px`;
            
            document.getElementById('siteLogo').textContent = config.site_title || defaultConfig.site_title;
            
            updateLanguage();
        }

        if (window.elementSdk) {
            window.elementSdk.init({
                defaultConfig: defaultConfig,
                onConfigChange: onConfigChange,
                mapToCapabilities: (config) => ({
                    recolorables: [
                        {
                            get: () => config.background_color || defaultConfig.background_color,
                            set: (value) => {
                                config.background_color = value;
                                window.elementSdk.setConfig({ background_color: value });
                            }
                        },
                        {
                            get: () => config.surface_color || defaultConfig.surface_color,
                            set: (value) => {
                                config.surface_color = value;
                                window.elementSdk.setConfig({ surface_color: value });
                            }
                        },
                        {
                            get: () => config.text_color || defaultConfig.text_color,
                            set: (value) => {
                                config.text_color = value;
                                window.elementSdk.setConfig({ text_color: value });
                            }
                        },
                        {
                            get: () => config.primary_action_color || defaultConfig.primary_action_color,
                            set: (value) => {
                                config.primary_action_color = value;
                                window.elementSdk.setConfig({ primary_action_color: value });
                            }
                        },
                        {
                            get: () => config.secondary_action_color || defaultConfig.secondary_action_color,
                            set: (value) => {
                                config.secondary_action_color = value;
                                window.elementSdk.setConfig({ secondary_action_color: value });
                            }
                        }
                    ],
                    borderables: [],
                    fontEditable: {
                        get: () => config.font_family || defaultConfig.font_family,
                        set: (value) => {
                            config.font_family = value;
                            window.elementSdk.setConfig({ font_family: value });
                        }
                    },
                    fontSizeable: {
                        get: () => config.font_size || defaultConfig.font_size,
                        set: (value) => {
                            config.font_size = value;
                            window.elementSdk.setConfig({ font_size: value });
                        }
                    }
                }),
                mapToEditPanelValues: (config) => new Map([
                    ["site_title", config.site_title || defaultConfig.site_title],
                    ["hero_title_id", config.hero_title_id || defaultConfig.hero_title_id],
                    ["hero_title_en", config.hero_title_en || defaultConfig.hero_title_en],
                    ["hero_subtitle_id", config.hero_subtitle_id || defaultConfig.hero_subtitle_id],
                    ["hero_subtitle_en", config.hero_subtitle_en || defaultConfig.hero_subtitle_en],
                    ["feature1_title_id", config.feature1_title_id || defaultConfig.feature1_title_id],
                    ["feature1_title_en", config.feature1_title_en || defaultConfig.feature1_title_en],
                    ["feature2_title_id", config.feature2_title_id || defaultConfig.feature2_title_id],
                    ["feature2_title_en", config.feature2_title_en || defaultConfig.feature2_title_en],
                    ["feature3_title_id", config.feature3_title_id || defaultConfig.feature3_title_id],
                    ["feature3_title_en", config.feature3_title_en || defaultConfig.feature3_title_en],
                    ["cta_button_id", config.cta_button_id || defaultConfig.cta_button_id],
                    ["cta_button_en", config.cta_button_en || defaultConfig.cta_button_en]
                ])
            });
        }
    </script>
 <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'99bc514bd3a5f904',t:'MTc2MjY4MDkwMC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>