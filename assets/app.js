// React
import { registerReactControllerComponents } from "@symfony/ux-react";

// Stimulus
import "./bootstrap.js";

// Styles
import "./styles/app.css";

// React
registerReactControllerComponents(
  require.context("./react/controllers", true, /\.(j|t)sx?$/)
);
