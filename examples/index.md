---
layout:  page
title:   Examples
gh-file: examples/index.md
---

Please select library part to show examples for:

<ul>
  {% assign sorted_pages = site.pages | sort:"name" %}
  {% for node in sorted_pages %}
	{% if node.categories contains "examples" %}
	  <li class="sidebar-nav-item{% if page.url == node.url %} active{% endif %}">
		<a href="{{ node.url }}">{{ node.title }}</a>
	  </li>
	{% endif %}
  {% endfor %}
</ul>
