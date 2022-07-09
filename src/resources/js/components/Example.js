import ReactDOM from 'react-dom';
import { useState } from 'react';

import './Example.css';
import { save, get } from '../services/player_stats';

function Example() {

    const [amount, setAmount] = useState(10);
    const [times, setTimes] = useState([]);

    const [orderBy, setOrderBy] = useState('goals');
    const [stats, setStats] = useState({});
    const executeSaveStats = (player_id, stats, start) => {
        save(player_id, stats).then(() => {
           const time = performance.now() - start;
            setTimes(prevData => 
                 [...prevData, { duration: time/1000, player_id: player_id }]
              )
        }).catch(err => {
            console.log(err);
        });
    }

    const generateStats = () => {

        setTimes([]);

        for (let i = 0; i < amount; i++) {

            const player_id = Math.floor(Math.random() * 100);

            const stats = [
                {
                    name: "goals",
                    value: Math.floor(Math.random() * 100)
                },
                {
                    name: "assists",
                    value: Math.floor(Math.random() * 100)
                },
                {
                    name: "points",
                    value: Math.floor(Math.random() * 100)
                },
                {
                    name: "games_played",
                    value: Math.floor(Math.random() * 100)
                },
                {
                    name: "shots",
                    value: Math.floor(Math.random() * 100)
                }
            ]
            let start = performance.now();
            executeSaveStats(player_id, stats, start);
        }

    }

    const getStats = () => {
        get(orderBy).then(stats => {
            setStats(stats.data);
        }).catch(err => {
            console.log(err);
        }
        );
    }

    return (
        <div className="container">
            <div className="col-6">
                <div className="card">
                    <h3 className="card-header">Generate Stats</h3>
                    <label>Number of petitions</label>
                    <input
                        value={amount}
                        onChange={(evt) => setAmount(evt.target.value)}
                    ></input>

                    <button onClick={generateStats} >Send</button>

                </div>
                {<div>
                    {times.map((time, index) => {
                        return <p key={index}>{(index + 1) + " - player_id:" + time.player_id + " - " + time.duration + ' seconds'} </p>
                    })}
                </div>}
            </div>
            <div className="col-6">
                <div className="card">
                    <h3 className="card-header">Get Stats</h3>
                    <label>Order By</label>
                    <input
                        value={orderBy}
                        onChange={(evt) => setOrderBy(evt.target.value)}
                    ></input>
                    <button onClick={getStats} >Send</button>
                </div>
                <pre>
                    {stats && JSON.stringify(stats, null, 2)}
                </pre>
            </div>
        </div>
    );
}

export default Example;

if (document.getElementById('root')) {
    ReactDOM.render(<Example />, document.getElementById('root'));
}
