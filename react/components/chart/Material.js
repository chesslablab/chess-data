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
    n: '1', w: 168, b: 168,
  },
  {
    n: '2', w: 168, b: 168,
  },
  {
    n: '3', w: 168, b: 168,
  },
  {
    n: '4', w: 168, b: 168,
  },
  {
    n: '5', w: 167, b: 167,
  },
  {
    n: '6', w: 167, b: 167,
  },
  {
    n: '7', w: 167, b: 167,
  },
  {
    n: '8', w: 166, b: 166,
  },
  {
    n: '9', w: 165, b: 165,
  },
  {
    n: '10', w: 165, b: 164,
  },
  {
    n: '11', w: 165, b: 164,
  },
  {
    n: '12', w: 165, b: 164,
  },
  {
    n: '13', w: 164, b: 164,
  },
  {
    n: '14', w: 164, b: 164,
  },
  {
    n: '15', w: 164, b: 164,
  },
  {
    n: '16', w: 164, b: 163,
  },
  {
    n: '17', w: 163, b: 163,
  },
  {
    n: '18', w: 160, b: 163,
  },
  {
    n: '19', w: 160, b: 163,
  },
  {
    n: '20', w: 160, b: 162,
  },
  {
    n: '21', w: 155, b: 161,
  },
  {
    n: '22', w: 155, b: 161,
  },
  {
    n: '23', w: 145, b: 158,
  },
  {
    n: '24', w: 140, b: 157,
  },
  {
    n: '25', w: 130, b: 155,
  },
];

export default function MaterialChart() {
  const classes = useStyles();
  const fixedHeightPaper = clsx(classes.paper, classes.fixedHeight);

  return (
    <Container maxWidth="lg" className={classes.container}>
      <Breadcrumbs aria-label="breadcrumb" className={classes.breadcrumbs}>
        <Typography color="textSecondary">Heuristic</Typography>
        <Typography color="textPrimary">Material</Typography>
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
