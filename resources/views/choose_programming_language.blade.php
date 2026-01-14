@extends('template')

@section('title', 'Choose a language')
    
@section('content')
    <div class="container-fluid bg-dark" style="height: 100vh; position: relative; overflow: hidden;">
        <div class="row justify-content-center align-items-center" style="height: 100%;">
            <div class="d-flex flex-column justify-content-center align-items-center" style="position: relative; z-index: 999;">
                @if (session('success'))
                    <div class="alert alert-success cg-success-toast w-75 text-center mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                <form id="cardForm" action="{{ route('select-language', ['userId' => $userId]) }}" method="POST" class="row align-items-center justify-content-center">
                    @csrf
                    <div class="d-flex justify-content-center align-items-center">
                        @foreach ($languages as $lang)
                            <div class="col-md-4" style="width: 12vw; margin: 20px">
                                <input type="checkbox" class="custom-checkbox" id="card{{$lang->id}}" name="selected_languages[]" value={{$lang->id}}>
                                <label class="card" for="card{{$lang->id}}">
                                    <img src="{{ asset('storage/' . $lang->programming_language_image_path) }}" class="card-img-top" alt="Language image">
                                    <div class="card-body">
                                        <h5 class="card-title">{{$lang->programming_language_name}}</h5>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-primary mt-3" style="width: max-content">Submit Selected Cards</button>
                </form>
            </div>
            <canvas id="particle-canvas" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></canvas>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // Prevent form submission on checkbox click
        $('.custom-checkbox').click(function(e) {
        e.stopPropagation();
        });
    });
    </script>
    <script>
        // Vector class
        class Vector {
            constructor(x, y) {
                this.x = x
                this.y = y
            }

            setValue(x, y) {
                this.x = x
                this.y = y
            }
        }

        // ParticleCanvas class
        class ParticleCanvas {
            constructor(canvas, mousePosition) {
                this.canvas = canvas
                this.ctx = canvas.getContext("2d")
                this.mousePosition = mousePosition
                this.particles = []
                this.numParticles = 100
            }

            init() {
                for (let i = 0; i < this.numParticles; i++) {
                    this.particles.push(new Particle(this.canvas.width, this.canvas.height))
                }
            }

            draw() {
                this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height)
                for (let i = 0; i < this.numParticles; i++) {
                    let particle = this.particles[i]
                    this.ctx.beginPath()
                    this.ctx.arc(particle.position.x, particle.position.y, particle.radius, 0, Math.PI * 2)
                    this.ctx.fillStyle = particle.color
                    this.ctx.fill()

                    // Interactivity with mouse
                    let distance = Math.sqrt(Math.pow(particle.position.x - this.mousePosition.x, 2) + Math.pow(particle.position.y - this.mousePosition.y, 2))
                    if (distance < 50) {
                        let acceleration = new Vector(this.mousePosition.x - particle.position.x, this.mousePosition.y - particle.position.y)
                        acceleration.setValue(acceleration.x * 0.02, acceleration.y * 0.02)
                        particle.velocity.x += acceleration.x
                        particle.velocity.y += acceleration.y
                    }

                    particle.update()
                }
                requestAnimationFrame(() => this.draw())
            }
        }

        // Particle class
        class Particle {
            constructor(canvasWidth, canvasHeight) {
                this.canvasWidth = canvasWidth
                this.canvasHeight = canvasHeight
                this.position = new Vector(Math.random() * canvasWidth, Math.random() * canvasHeight)
                this.velocity = new Vector(Math.random() - 0.5, Math.random() - 0.5)
                this.radius = Math.random() * 3 + 1
                this.color = `rgba(255, 255, 255, ${Math.random()*0.5})`
            }

            update() {
                this.position.x += this.velocity.x
                this.position.y += this.velocity.y

                if (this.position.x > this.canvasWidth + this.radius) {
                    this.position.x = -this.radius
                }
                if (this.position.x < -this.radius) {
                    this.position.x = this.canvasWidth + this.radius
                }
                if (this.position.y > this.canvasHeight + this.radius) {
                    this.position.y = -this.radius
                }
                if (this.position.y < -this.radius) {
                    this.position.y = this.canvasHeight + this.radius
                }
            }
        }

        // Initialization
        (function () {
            let canvas = document.getElementById("particle-canvas")
            canvas.width = window.innerWidth
            canvas.height = window.innerHeight

            let mousePosition = new Vector(0, 0)
            window.addEventListener("mousemove", function (e) {
                mousePosition.setValue(e.clientX, e.clientY)
            })

            let particleCanvas = new ParticleCanvas(canvas, mousePosition)
            particleCanvas.init()
            particleCanvas.draw()
        })();
    </script>
    <style>
        .cg-success-toast {
            animation: cg-slide-up 0.6s ease-out both;
            margin: 0 auto;
        }
        @keyframes cg-slide-up {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .card {
            cursor: pointer;
            border: 2px solid #fff;
            text-align: center;
        }
        .card-img-top {
            display: block;
            margin: 0 auto;
            max-width: 64px;
            height: auto;
        }
        .card-title {
            color: #111;
            text-align: center;
        }
        .custom-checkbox {
            display: none; /* Hide the actual checkbox */
        }
        .custom-checkbox + .card {
            border: 2px solid transparent;
        }
        .custom-checkbox:checked + .card {
            border: 2px solid blue; /* Highlight the card when selected */
        }
    </style>
@endsection

