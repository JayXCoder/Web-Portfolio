/**
 * Hero terminal snippets — language showcase (no stack/product name-drops).
 */
export const HERO_CODE_SNIPPETS = [
    {
        language: 'Python',
        text: 'async def ship_it():\n    ideas = ["automate", "optimize", "repeat"]\n    for move in ideas:\n        await ignite(move)\n    return "another boundary gone"',
    },
    {
        language: 'JavaScript',
        text: 'const future = ["neural", "edge", "realtime"];\nconst vibe = future.map(x => x.toUpperCase());\nconsole.log(`sync: ${vibe.join(" · ")}`);\n// stay curious, stay building',
    },
    {
        language: 'TypeScript',
        text: 'type Blueprint<T> = {\n  payload: T;\n  retries: number;\n};\n\nconst launch = <T,>(plan: Blueprint<T>) =>\n  plan.retries ? "resilient" : "bold";',
    },
    {
        language: 'PHP',
        text: '$signals = ["create", "refactor", "ship"];\n$pulse = array_map(fn ($s) => strtoupper($s), $signals);\necho implode(" → ", $pulse);\n// midnight commits hit different',
    },
    {
        language: 'Java',
        text: 'record Mission(String goal, int fuel) {\n  public String execute() {\n    return fuel > 0 ? "orbiting: " + goal : "recharge";\n  }\n}',
    },
    {
        language: 'C++',
        text: '#include <vector>\n#include <iostream>\n\nint main() {\n  std::vector<std::string> sparks = {"low", "level", "magic"};\n  for (auto& s : sparks) std::cout << s << "\\n";\n}',
    },
    {
        language: 'C#',
        text: 'var grid = Enumerable.Range(1, 5)\n    .Select(n => n * n)\n    .Where(x => x % 2 == 0);\n\nConsole.WriteLine(string.Join(", ", grid));',
    },
    {
        language: 'Rust',
        text: 'fn main() {\n    let stack = vec!["safe", "fast", "fearless"];\n    let motto = stack.join(" · ");\n    println!("forge: {}", motto);\n}',
    },
    {
        language: 'Go',
        text: 'package main\n\nimport "fmt"\n\nfunc main() {\n\tch := make(chan string, 1)\n\tch <- "concurrency unlocked"\n\tfmt.Println(<-ch)\n}',
    },
    {
        language: 'Kotlin',
        text: 'suspend fun pulse(): List<String> =\n    (1..3).map { step ->\n        "phase-$step: online"\n    }\n\nfun main() = println(pulse())',
    },
    {
        language: 'Swift',
        text: 'enum Mode {\n    case explore, focus, ship\n}\n\nlet today: Mode = .focus\nprint("state:", today)',
    },
    {
        language: 'Dart',
        text: "void main() {\n  final layers = ['ui', 'logic', 'state'];\n  for (final layer in layers) {\n    print('crafting $layer');\n  }\n}",
    },
    {
        language: 'SQL',
        text: "SELECT path, COUNT(*) AS wins\nFROM experiments\nWHERE status = 'shipped'\nGROUP BY path\nORDER BY wins DESC\nLIMIT 5;",
    },
    {
        language: 'HTML',
        text: '<section class="signal">\n  <h1>Ideas → Interfaces</h1>\n  <p>Pixels with purpose.</p>\n  <button type="button">Init</button>\n</section>',
    },
    {
        language: 'Bash',
        text: '#!/usr/bin/env bash\nset -euo pipefail\n\necho "compiling courage..."\nfor i in {1..3}; do\n  printf "tick %s\\n" "$i"\ndone',
    },
    {
        language: 'Assembly',
        text: '; boot the dream machine\nsection .text\nglobal _start\n_start:\n    mov rax, 1\n    mov rdi, 1\n    int 0x80',
    },
];
