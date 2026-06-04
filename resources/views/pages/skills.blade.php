@extends('layouts.app')

@section('title', 'Skills — Jawahar Ganesh @ Jay')

@section('content')
<section class="section-pad">
    <div class="site-container">
        <h1 class="section-title fade-in-view">Skills</h1>
        <p class="section-subtitle fade-in-view">Technologies I use to ship end-to-end.</p>

        @php
        $groups = [
            'Languages' => ['PHP', 'JavaScript', 'TypeScript', 'Python', 'C/C++', 'SQL'],
            'Web' => ['Laravel', 'React', 'Vue', 'REST APIs', 'Tailwind', 'Bootstrap'],
            'AI / Data' => ['Machine Learning', 'Ollama', 'TensorFlow', 'Pandas', 'OpenCV'],
            'DevOps & Security' => ['Docker', 'Linux', 'Git', 'CI/CD', 'Penetration Testing'],
            'Hardware' => ['Arduino', 'Raspberry Pi', 'ESP32', 'IoT', 'Embedded C'],
        ];
        @endphp

        <div class="mt-12 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach($groups as $title => $skills)
            <article class="card-surface fade-in-view p-6">
                <h2 class="font-display text-lg font-semibold text-uv-bright">{{ $title }}</h2>
                <ul class="mt-4 flex flex-wrap gap-2">
                    @foreach($skills as $skill)
                    <li class="badge-uv">{{ $skill }}</li>
                    @endforeach
                </ul>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endsection
