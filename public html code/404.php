<?php get_header(); ?>

<style>
    body {
        font-family: 'Arial', sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background: linear-gradient(135deg, #f3ec78, #af4261);
        color: #333;
        text-align: center;
        padding: 20px;
        margin: 0;
    }

    .container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        padding: 40px;
        max-width: 500px;
        width: 100%;
        position: relative;
        overflow: hidden;
    }

    .container::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1));
        animation: pulse 6s infinite;
        z-index: 0;
    }

    @keyframes pulse {
        0% {
            transform: scale(0.9);
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(0.9);
        }
    }

    .content {
        position: relative;
        z-index: 1;
    }

    h1 {
        font-size: 50px;
        margin: 0;
        color: #e63946;
        text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
    }

    p {
        font-size: 20px;
        margin: 15px 0;
        color: #555;
    }

    .emoji {
        font-size: 70px;
    }

    .home-button {
        background: #f1faee;
        color: #1d3557;
        border: none;
        border-radius: 25px;
        padding: 15px 30px;
        font-size: 18px;
        cursor: pointer;
        transition: background 0.3s, transform 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .home-button:hover {
        background: #a8dadc;
        transform: translateY(-3px);
    }

    @media (max-width: 768px) {
        h1 {
            font-size: 40px;
        }

        p {
            font-size: 18px;
        }
    }
</style>

<div class="container">
    <div class="content">
        <h1>404</h1>
        <div class="emoji">🤷‍♂️</div>
        <p>Fingers Not Found!</p>
        <p>Looks like you lost your fingers trying to find this page.</p>
        <a href="<?php echo home_url(); ?>" class="home-button">Go Back Home</a>
    </div>
</div>

<?php get_footer(); ?>
