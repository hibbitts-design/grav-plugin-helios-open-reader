---
title: 'Callout Blocks'
description: 'All eleven callout blocks demonstrated in context, with syntax and usage notes.'
---

[objectives]
- Identify the eleven callout blocks available in Helios Open Reader
- Apply callout blocks in your own reader content
- Choose the appropriate callout type for different instructional purposes
[/objectives]

Callout blocks are structured, visually styled instructional elements you can drop into any page. All callouts follow the same syntax:

[raw]
```
[callout-name]
Content here – supports Markdown.
[/callout-name]
```
[/raw]

Add an optional custom title with `title="..."`:

[raw]
```
[callout-name title="Custom Title"]
Content here.
[/callout-name]
```
[/raw]

## Learning Objectives – [raw]`[objectives]`[/raw]

The [raw]`[objectives]`[/raw] callout renders a green Learning Objectives block. Use it at the start of a section to set expectations. The same content can also be set as `learning_objectives:` in page frontmatter – it renders automatically at the top of the section page without a shortcode in the body.

## Key Takeaways – [raw]`[key-takeaways]`[/raw]

[key-takeaways]
- All eleven callouts use the same `[name]...[/name]` syntax
- Every callout accepts `title="..."` for a custom heading
- `[objectives]` content can also come from the `learning_objectives:` frontmatter field
- `[project-brief]`, `[feedback-requested]`, and `[process-note]` support student projects and draft OER
- GitHub-style callouts (`> [!TIP]`) are a lightweight alternative for inline notices
[/key-takeaways]

Use [raw]`[key-takeaways]`[/raw] (blue) at the end of a section to summarise the most important points.

## Definition – [raw]`[definition]`[/raw]

[definition title="Frontmatter"]
YAML metadata placed at the top of a Markdown file between `---` delimiters. Grav uses frontmatter fields to control page settings – title, template, section number, callout content, and more – without embedding configuration in the body of the page.
[/definition]

Use [raw]`[definition]`[/raw] (blue) to define key terms inline in your content.

## Example – [raw]`[example]`[/raw]

[example title="Using learning_objectives in frontmatter"]
Instead of placing an `[objectives]` shortcode at the top of every section page, set the `learning_objectives:` field in the page frontmatter:

```yaml
learning_objectives: |
  - Define open education and explain its core principles
  - Identify the 5Rs of open educational resources
```

The Learning Objectives block renders automatically at the top of the page – no shortcode required in the body.
[/example]

Use [raw]`[example]`[/raw] (purple) for worked examples and illustrations of a concept.

## Exercise – [raw]`[exercise]`[/raw]

[exercise title="Add a callout block to your reader"]
Open any section page in your reader and add one of the following to the page content:

1. A `[key-takeaways]` block summarising the three most important points on the page
2. A `[definition]` block defining a key term used in the section
3. A `[reflection]` block posing a question for the reader to consider

Save the page and preview it in the browser to confirm the callout renders correctly.
[/exercise]

Use [raw]`[exercise]`[/raw] (amber) for tasks or activities that ask the reader to do something.

## Reflection – [raw]`[reflection]`[/raw]

[reflection title="Which callout types fit your content?"]
Look through the content you plan to publish. Ask yourself:

- Do you have explicit learning goals that belong in a `[objectives]` block?
- Are there key terms that would benefit from `[definition]` callouts?
- Could a `[case-study]` ground an abstract concept in a real-world example?

There is no rule about how many callouts to use – some pages warrant several; others none. Use them where they genuinely improve comprehension, not as decoration.
[/reflection]

Use [raw]`[reflection]`[/raw] (green) to invite metacognitive engagement or personal response from the reader.

## Case Study – [raw]`[case-study]`[/raw]

[case-study title="Publishing a Collaborative Open Reader"]
A team of three instructors set up a Helios Open Reader to replace a course pack of photocopied readings. They organised content into five sections – one per week – with Learning Objectives in frontmatter and `[example]` callouts drawn from their own discipline. Git Sync kept the Grav site in sync with a private GitHub repository, allowing all three instructors to edit pages independently. After one semester of use and revision, they released the reader publicly under CC BY 4.0. Within two terms, instructors at two other institutions had adapted sections for their own courses and submitted pull requests with corrections and additions.
[/case-study]

Use [raw]`[case-study]`[/raw] (red) for real-world scenarios that situate a concept in practice.

## Announcement – [raw]`[announcement]`[/raw]

[announcement title="For simple inline notices, GitHub-style callouts are zero-friction"]
`> [!NOTE]`, `> [!TIP]`, `> [!IMPORTANT]`, `> [!WARNING]`, and `> [!CAUTION]` are lightweight alternatives to the [raw]`[announcement]`[/raw] shortcode. Use them for brief inline notices that don't need a formal block structure.
[/announcement]

Use [raw]`[announcement]`[/raw] (purple by default) for notices, updates, or time-sensitive alerts. Set `type="note"`, `type="tip"`, `type="warning"`, or `type="caution"` to change the colour.

## Project Brief – [raw]`[project-brief]`[/raw]

[project-brief title="Publish an Open Reader"]
Choose a set of readings or materials you currently share via a course pack, LMS file list, or email. Organise them into 3–5 sections, write a short description for each, and publish them as a Helios Open Reader with CC licensing and OER attribution. The reader should be self-contained — a reader who finds it without any other context should understand what it is and who it is for.
[/project-brief]

Use [raw]`[project-brief]`[/raw] (amber) to frame the assignment or challenge prompt at the top of a project page, clearly distinguishing the task from the student's own response.

## Feedback Requested – [raw]`[feedback-requested]`[/raw]

[feedback-requested title="Feedback Requested – Section Structure"]
We're unsure whether splitting Installation into two sub-pages (Requirements and Steps) adds clarity or just extra clicks. Does the separation feel natural to you, or would a single page flow better?
[/feedback-requested]

Use [raw]`[feedback-requested]`[/raw] (purple) to flag specific content awaiting review — from classmates in a student project, from peers reviewing a draft OER, or from an instructor evaluating work in progress.

## Process Note – [raw]`[process-note]`[/raw]

[process-note title="Initial structure vs. final structure"]
Originally planned four sections mirroring the course schedule, but the third and fourth sections had too much overlap. Merged them into a single section on publishing and sharing, which made the navigation much cleaner. The OER attribution section was added late after realising the CC license badge alone wasn't enough context for readers unfamiliar with Creative Commons.
[/process-note]

Use [raw]`[process-note]`[/raw] (blue) in student project pages to document iterations, pivots, and decisions made during the work — showing the process as well as the outcome.
