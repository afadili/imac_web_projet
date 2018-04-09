import React, { Component } from 'react';
import logo from './logo_test_01.png';
import './App.css';

class App extends Component {
  render() {
    return (
      <div className="App">
        <header className="App-header">
          <img src={logo} className="App-logo" alt="logo" />
          <h1 className="App-title">emoji tracker</h1>
        </header>
        <p className="App-intro">
          What's the tweetosphere's mood today ?
        </p>
      </div>
    );
  }
}

export default App;
