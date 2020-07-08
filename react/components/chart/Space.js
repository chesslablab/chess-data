import React from 'react';
import { CartesianGrid, Legend, LineChart, Line, XAxis, YAxis, ResponsiveContainer, Tooltip } from 'recharts';
import clsx from 'clsx';
import useStyles from 'components/chart/styles';
import Breadcrumbs from '@material-ui/core/Breadcrumbs';
import Container from '@material-ui/core/Container';
import Grid from '@material-ui/core/Grid';
import Movetext from 'components/Movetext';
import Paper from '@material-ui/core/Paper';
import SevenTagRoster from 'components/SevenTagRoster';
import Typography from '@material-ui/core/Typography';

const data = [
  {
    n: '1', w: 0, b: 0,
  },
  {
    n: '2', w: 15, b: 15,
  },
  {
    n: '3', w: 20, b: 18,
  },
  {
    n: '4', w: 19, b: 21,
  },
  {
    n: '5', w: 23, b: 20,
  },
  {
    n: '6', w: 25, b: 20,
  },
  {
    n: '7', w: 24, b: 25,
  },
  {
    n: '8', w: 45, b: 22,
  },
  {
    n: '9', w: 40, b: 35,
  },
  {
    n: '10', w: 42, b: 42,
  },
  {
    n: '11', w: 45, b: 50,
  },
  {
    n: '12', w: 46, b: 55,
  },
  {
    n: '13', w: 40, b: 50,
  },
  {
    n: '14', w: 42, b: 53,
  },
  {
    n: '15', w: 43, b: 55,
  },
  {
    n: '16', w: 33, b: 57,
  },
  {
    n: '17', w: 41, b: 54,
  },
  {
    n: '18', w: 55, b: 60,
  },
  {
    n: '19', w: 32, b: 61,
  },
  {
    n: '20', w: 40, b: 61,
  },
  {
    n: '21', w: 35, b: 67,
  },
  {
    n: '22', w: 36, b: 72,
  },
  {
    n: '23', w: 37, b: 65,
  },
  {
    n: '24', w: 38, b: 67,
  },
  {
    n: '25', w: 40, b: 64,
  },
];

export default function SpaceChart() {
  const classes = useStyles();
  const fixedHeightPaper = clsx(classes.paper, classes.fixedHeight);

  return (
    <Container maxWidth="lg" className={classes.container}>
      <Breadcrumbs aria-label="breadcrumb" className={classes.breadcrumbs}>
        <Typography color="textSecondary">Heuristic</Typography>
        <Typography color="textPrimary">Space</Typography>
      </Breadcrumbs>
      <Grid container spacing={3}>
        <Grid item xs={12} md={8} lg={9}>
          <Paper className={fixedHeightPaper}>
            <React.Fragment>
              <ResponsiveContainer>
                <LineChart
                  width={500}
                  height={300}
                  data={data}
                  margin={{
                    top: 5, right: 30, left: 20, bottom: 5,
                  }}
                >
                  <CartesianGrid strokeDasharray="3 3" />
                  <XAxis dataKey="n" />
                  <YAxis />
                  <Tooltip />
                  <Legend />
                  <Line type="monotone" dataKey="w" stroke="#82ca9d" fill="#82ca9d" strokeWidth={2} />
                  <Line type="monotone" dataKey="b" stroke="#8884d8" fill="#8884d8" strokeWidth={2} activeDot={{ r: 8 }} />
                </LineChart>
              </ResponsiveContainer>
            </React.Fragment>
          </Paper>
        </Grid>
        <Grid item xs={12} md={4} lg={3}>
          <Paper className={fixedHeightPaper}>
            <SevenTagRoster />
          </Paper>
        </Grid>
        <Grid item xs={12}>
          <Paper>
            <Movetext />
          </Paper>
        </Grid>
      </Grid>
    </Container>
  );
}
